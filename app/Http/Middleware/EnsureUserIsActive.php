<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (int) $user->status !== User::ACTIVO) {
            
            if (! $request->routeIs('cuenta.pendiente') && ! $request->routeIs('logout')) {
                return redirect()->route('cuenta.pendiente');
            }
        }

        return $next($request);
    }
}