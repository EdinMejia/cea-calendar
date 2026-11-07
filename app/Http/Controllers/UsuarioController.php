<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->query('buscar'));
        $filtroRol = trim((string) $request->query('rol'));

        $usuarios = User::where('status', '!=', User::PENDIENTE)
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('name_one', 'like', "%{$busqueda}%")
                    ->orWhere('last_name_one', 'like', "%{$busqueda}%");
                });
            })
            ->with('roles')
            ->orderBy('name_one')
            ->get();
        $roles = Role::orderBy('name')->pluck('name');
        return view('usuarios.index', compact('usuarios', 'roles', 'busqueda', 'filtroRol'));
    }

    public function solicitudes(Request $request)
    {
        $busqueda = trim((string) $request->query('busqueda'));
        $solicitudes = User::pendientes()
            ->when($busqueda !== '', function ($query) use ($busqueda){
                $query->where(function ($q) use ($busqueda) {
                    $q->where('name_one', 'like', "%{$busqueda}%")
                        ->orWhere('name_two', 'like', "%{$busqueda}%")
                        ->orWhere('last_name_one', 'like', "%{$busqueda}%")
                        ->orWhere('last_name_two', 'like', "%{$busqueda}%")
                        ->orWhere('email', 'like', "%{$busqueda}%");
                });
            })
            ->orderBy('created_at')
            ->get(); 

        $roles = Role::orderBy('name')->pluck('name');

        return view('usuarios.solicitudes', compact('solicitudes', 'roles', 'busqueda'));
    }
    
    public function aprobar(Request $request, User $user)
    {
        abort_unless((int) $user->status === User::PENDIENTE, 404);
        $data = $request->validate([
            'rol' => ['required', Rule::exists('roles', 'name')->where('guard_name', 'web')],
        ]);

        DB::transaction(function () use ($user, $data) {
            $user->syncRoles([$data['rol']]);
            $user->update(['status' => User::ACTIVO]);
        });

        return response()->json([
            'success' => true,
            'message' => "{$user->name_one} ahora tiene acceso como {$data['rol']}."
        ]);
    }

    public function rechazar(User $user)
    {
        abort_unless((int) $user->status === User::PENDIENTE, 404);
        
        $user->update(['status' => User::INACTIVO]);
        
        return response()->json([
            'success' => true,
            'message' => "La solicitud ha sido rechazada."
        ]);
    }
}
