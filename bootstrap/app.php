<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'activo'             => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (HttpException $e, Request $request) {

            if ($e->getStatusCode() !== 403) {
                return null;                       // otros errores siguen normal
            }

            $usuario = $request->user();

            // Peticiones fetch/AJAX, usuarios sin sesión, quien no puede ver ni el
            // dashboard o ya está en él: se deja la página 403 (evita bucles)
            if ($request->expectsJson() || ! $usuario
                || ! $usuario->can('actividades.ver') || $request->routeIs('dashboard')) {
                return null;
            }

            return redirect()->route('dashboard')
                ->with('error', 'No tienes acceso a esta acción.');
        });

    })->create();