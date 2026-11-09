<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
class UsuarioController extends Controller
{
    private const ROL_ADMIN = 'Administrador';

    private const CAMPOS_BUSQUEDA = ['name_one', 'name_two', 'last_name_one', 'last_name_two', 'email'];

    public function index(Request $request)
    {
        $busqueda  = trim((string) $request->query('buscar'));
        $filtroRol = trim((string) $request->query('rol'));

        $usuarios = User::where('status', '!=', User::PENDIENTE)
            ->when($busqueda !== '', fn ($q) => $this->buscar($q, $busqueda))
            ->when($filtroRol !== '', fn ($q) => $q->whereHas('roles', fn ($r) => $r->where('name', $filtroRol)))
            ->with('roles')
            ->orderBy('name_one')
            ->get();

        $roles = Role::orderBy('name')->pluck('name');

        return view('usuarios.index', compact('usuarios', 'roles', 'busqueda', 'filtroRol'));
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

        // El rol Administrador no se asigna desde aquí
        $roles = Role::where('name', '!=', self::ROL_ADMIN)->orderBy('name')->pluck('name');

        return view('usuarios.solicitudes', compact('solicitudes', 'historial', 'roles', 'buscar'));
    }

    public function aprobar(Request $request, User $user)
    {
        // Acepta pendientes y también rechazados (así funciona "Restaurar")
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

        // Se guarda ANTES de actualizar: después, el estado anterior ya se perdió
        $restaurado = (int) $user->status === User::INACTIVO;

        DB::transaction(function () use ($user, $data) {
            $user->syncRoles([$data['rol']]);
            $user->update(['status' => User::ACTIVO]);
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
        });

        return redirect()->route('usuarios.solicitudes')
            ->with('success', "La solicitud de {$this->nombre($user)} fue rechazada y pasó al historial.");
    }

    public function eliminar(User $user)
    {
        // Solo se pueden eliminar cuentas que ya fueron rechazadas
        abort_unless((int) $user->status === User::INACTIVO, 404);
        abort_if($user->id === Auth::id(), 403);

        // Si ya creó actividades, borrarlo dejaría datos huérfanos
        // (si algún día activas SoftDeletes en Actividad, usa ->withTrashed() aquí)
        if (Actividad::where('user_id', $user->id)->exists()) {
            return redirect()->route('usuarios.solicitudes')
                ->with('error', "No se puede eliminar a {$this->nombre($user)}: tiene actividades registradas.");
        }

        $nombre = $this->nombre($user);

        DB::transaction(function () use ($user) {
            DB::table('comision_user')->where('user_id', $user->id)->delete();
            $user->delete();   // Spatie también limpia sus roles y permisos
        });

        return redirect()->route('usuarios.solicitudes')
            ->with('success', "{$nombre} fue eliminado definitivamente del sistema.");
    }

    // ---------- Ayudas privadas ----------

    private function buscar(Builder $query, string $texto): Builder
    {
        // addcslashes: un % o _ escrito en el buscador no actúa como comodín
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
}