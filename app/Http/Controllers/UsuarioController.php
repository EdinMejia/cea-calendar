<?php

namespace App\Http\Controllers;

use App\Models\AccesoBitacora; 
use App\Models\Actividad;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class UsuarioController extends Controller
{
    private const ROL_ADMIN = 'Admin'; 
    private const CAMPOS_BUSQUEDA = ['name_one', 'name_two', 'last_name_one', 'last_name_two', 'email'];

    private const GRUPOS = [
        'actividades' => ['Actividades', ['crear'=>'Crear', 'editar'=>'Editar', 'borrar'=>'Borrar']],
        'comisiones' => ['Comisiones', ['crear'=>'Crear', 'editar'=>'Editar', 'borrar'=>'Borrar']],
        'usuarios' => ['Usuarios', ['ver'=>'Ver usuarios']],
    ];

    public function index(Request $request)
    {

        $usuarios = User::where('status', User::ACTIVO)
            ->with('roles', 'permissions')
            ->orderBy('name_one')
            ->get();

        $roles = Role::where('guard_name', 'web')->orderBy('name')->pluck('name');

        $datos = [
            'busqueda'  => trim((string) $request->query('buscar')),
            'filtroRol' => trim((string) $request->query('rol')),
            'usuarios'  => $usuarios->map(fn ($u) => [
                'id'       => $u->id,
                'nombre'   => $u->name,
                'rol'      => $u->roles->first()?->name ?? '',
                'permisos' => $u->permissions->pluck('name')->values(),   
                'busqueda' => Str::ascii(mb_strtolower($u->name.' '.$u->email.' '.($u->roles->first()?->name ?? ''))),
            ])->values(),
            'permisosDeRol' => Role::with('permissions')->get()
            ->mapWithKeys(fn ($r) => [$r->name => $r->permissions->pluck('name')->values()]),
            'urlActualizar' => route('usuarios.acceso.actualizar', ['user' => '__ID__']),
        ];
        
        return view('usuarios.index', [
            'usuarios'       => $usuarios,
            'roles'          => $roles,
            'gruposPermisos' => $this->gruposPermisos(),
            'pendientes'     => User::where('status', User::PENDIENTE)->count(),
            'yoId'           => Auth::id(),
            'datos'          => $datos,
        ]);
    }

    public function solicitudes(Request $request)
    {
        $buscar = trim((string) $request->query('buscar'));

        $solicitudes = User::where('status', User::PENDIENTE)
            ->when($buscar !== '', fn ($q) => $this->buscar($q, $buscar))
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pendientes_page')
            ->withQueryString();

        $historial = User::where('status', User::INACTIVO)
            ->when($buscar !== '', fn ($q) => $this->buscar($q, $buscar))
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'historial_page')
            ->withQueryString();
        
        $bitacora = AccesoBitacora::latest('id')
            ->paginate(10, ['*'], 'bitacora_page')
            ->withQueryString();

        $roles = Role::where('name', '!=', self::ROL_ADMIN)->orderBy('name')->pluck('name');

        return view('usuarios.solicitudes', compact('solicitudes', 'historial', 'roles', 'buscar', 'bitacora'));
    }

    public function aprobar(Request $request, User $user)
    {
        abort_unless(in_array((int) $user->status, [User::PENDIENTE, User::INACTIVO], true), 404);

        $data = $request->validate([
            'rol' => [
                'required',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
                Rule::notIn([self::ROL_ADMIN]),
            ],
        ], [
            'rol.required' => 'Elige un rol para dar acceso.',
            'rol.exists'   => 'El rol elegido no es válido.',
            'rol.not_in'   => 'Ese rol no se puede asignar desde solicitudes.',
        ]);

        $restaurado = (int) $user->status === User::INACTIVO;

        DB::transaction(function () use ($user, $data, $restaurado) {
            $user->syncRoles([$data['rol']]);
            $user->update(['status' => User::ACTIVO]);
            
            $this->registrar($user, $restaurado ? 'RESTAURADO' : 'APROBADO', 'Se otorgó acceso como: ' . $data['rol']);
        });

        $prefijo = $restaurado ? 'Restaurado' : 'Aprobado';

        return redirect()->route('usuarios.solicitudes')
            ->with('success', "{$prefijo}: {$this->nombre($user)} ahora tiene acceso como {$data['rol']}.");
    }

    public function rechazar(User $user)
    {
        abort_unless((int) $user->status === User::PENDIENTE, 404);
        abort_if($user->id === Auth::id(), 403);

        DB::transaction(function () use ($user) {
            $user->update(['status' => User::INACTIVO]);
            $user->syncRoles([]);
            
            $this->registrar($user, 'RECHAZADO', 'Se denegó la solicitud de acceso.');
        });

        return redirect()->route('usuarios.solicitudes')
            ->with('success', "La solicitud de {$this->nombre($user)} fue rechazada y pasó al historial.");
    }

    public function eliminar(User $user)
    {
        abort_unless((int) $user->status === User::INACTIVO, 404);
        abort_if($user->id === Auth::id(), 403);

        if (Actividad::where('user_id', $user->id)->exists()) {
            return redirect()->route('usuarios.solicitudes')
                ->with('error', "No se puede eliminar a {$this->nombre($user)}: tiene actividades registradas.");
        }

        $nombre = $this->nombre($user);

        DB::transaction(function () use ($user) {
            $this->registrar($user, 'ELIMINADO', 'Usuario eliminado definitivamente de la base de datos.');
            
            DB::table('comision_user')->where('user_id', $user->id)->delete();
            $user->delete(); 
        });

        return redirect()->route('usuarios.solicitudes')
            ->with('success', "{$nombre} fue eliminado definitivamente del sistema.");
    }

    public function actualizarAcceso(Request $request, User $user)
    {
        abort_unless((int) $user->status === User::ACTIVO, 404);

        if ($user->id === Auth::id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes modificar tu propio acceso.');
        }

        $gestionables = $this->permisosGestionables();

        $data = $request->validate([
            'rol'        => ['required', Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'permisos'   => ['nullable', 'array'],
            'permisos.*' => ['string', Rule::in($gestionables)],
        ], [
            'rol.required' => 'Elige un rol para este usuario.',
            'rol.exists'   => 'El rol elegido no es válido.',
        ]);

        if ($user->hasRole(self::ROL_ADMIN) && $data['rol'] !== self::ROL_ADMIN && $this->totalAdmins() <= 1) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes quitar el rol al único administrador del sistema.');
        }

        DB::transaction(function () use ($user, $data, $gestionables) {
            $user->syncRoles([$data['rol']]);

            $fuera = $user->permissions->pluck('name')->diff($gestionables)->all();
            $nuevosPermisos = $data['permisos'] ?? [];
            $user->syncPermissions(array_merge($fuera, $nuevosPermisos));
            
            $this->registrar($user, 'ACTUALIZADO', 'Nuevo rol: ' . $data['rol']);
        });

        return redirect()->route('usuarios.index')
            ->with('success', "Se actualizó el acceso de {$this->nombre($user)}.");
    }

    public function desactivar(User $user)
    {
        abort_unless((int) $user->status === User::ACTIVO, 404);
        abort_if($user->id === Auth::id(), 403);

        if ($user->hasRole(self::ROL_ADMIN) && $this->totalAdmins() <= 1) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes desactivar al único administrador del sistema.');
        }

        DB::transaction(function () use ($user) {
            $user->update(['status' => User::INACTIVO]);
            $user->syncRoles([]);
            $user->syncPermissions([]);
            
            $this->registrar($user, 'DESACTIVADO', 'El usuario perdió acceso y pasó a inactivos.');
        });

        return redirect()->route('usuarios.index')
            ->with('success', "{$this->nombre($user)} fue desactivado y pasó al historial de solicitudes.");
    }

    private function registrar(User $user, string $accion, ?string $detalle = null): void
    {
        AccesoBitacora::create([
            'user_id'       => $user->id,
            'usuario'       => trim("{$user->name_one} {$user->last_name_one}"),
            'correo'        => $user->email,
            'accion'        => mb_strtoupper($accion),
            'detalle'       => $detalle,
            'realizado_por' => Auth::user()->email,
        ]);
    }

    private function buscar(Builder $query, string $texto): Builder
    {
        $like = '%'.addcslashes($texto, '%_\\').'%';

        return $query->where(function ($q) use ($like) {
            foreach (self::CAMPOS_BUSQUEDA as $campo) {
                $q->orWhere($campo, 'like', $like);
            }
        });
    }

    private function nombre(User $user): string
    {
        return trim("{$user->name_one} {$user->last_name_one}");
    }

    private function gruposPermisos(): array
    {
        $existentes = Permission::where('guard_name', 'web')->pluck('name')->all();
        $grupos = [];

        foreach (self::GRUPOS as $clave => [$titulo, $acciones]) {
            $items = [];
            foreach ($acciones as $accion => $etiqueta) {
                if (in_array("$clave.$accion", $existentes, true)) {
                    $items[] = ['name' => "$clave.$accion", 'label' => $etiqueta];
                }
            }
            if ($items) {
                $grupos[] = ['titulo' => $titulo, 'permisos' => $items];
            }
        }

        return $grupos;
    }

    private function permisosGestionables(): array
    {
        return collect($this->gruposPermisos())->pluck('permisos')->flatten(1)->pluck('name')->all();
    }

    private function totalAdmins(): int
    {
        return User::role(self::ROL_ADMIN)->where('status', User::ACTIVO)->count();
    }
}