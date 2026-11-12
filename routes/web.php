<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'activo'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // inicio, detalle de actividad y reportes PDF 
    Route::middleware('permission:actividades.ver')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/actividades', [ActividadController::class, 'index'])->name('actividades.index');
        Route::get('/actividades/{actividad}', [ActividadController::class, 'show'])
            ->whereNumber('actividad')->name('actividades.show');

        // Reportes
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/semana', [ReporteController::class, 'semana'])->name('semana');
            Route::get('/mes', [ReporteController::class, 'mes'])->name('mes');
            Route::get('/anio', [ReporteController::class, 'anio'])->name('anio');
            Route::get('/actividad/{actividad}', [ReporteController::class, 'actividad'])
                ->whereNumber('actividad')->name('actividad');
        });
    });

    // Historial de actividades 
    Route::get('/actividades/historial', [ActividadController::class, 'historial'])
        ->middleware('permission:actividades.historial')->name('actividades.historial');

    // Crear, editar y eliminar actividades
    Route::middleware('permission:actividades.gestionar')->group(function () {
        Route::get('/actividades/crear', [ActividadController::class, 'create'])->name('actividades.create');
        Route::post('/actividades', [ActividadController::class, 'store'])->name('actividades.store');
        
        Route::prefix('/actividades/{actividad}')->whereNumber('actividad')->group(function () {
            Route::get('/editar', [ActividadController::class, 'edit'])->name('actividades.edit');
            Route::put('/', [ActividadController::class, 'update'])->name('actividades.update');
            Route::delete('/', [ActividadController::class, 'destroy'])->name('actividades.destroy');
        });
    });

    // Docentes y comisiones
    Route::middleware('permission:comisiones.gestionar')->group(function () {
        Route::get('/comisiones', [ComisionController::class, 'index'])->name('comisiones.index');
        Route::get('/comisiones/crear', [ComisionController::class, 'create'])->name('comisiones.create');
        Route::post('/comisiones', [ComisionController::class, 'store'])->name('comisiones.store');
        Route::post('/comisiones/ajax', [ComisionController::class, 'storeAjax'])->name('comisiones.ajax');
        Route::get('/comisiones/{id}/editar', [ComisionController::class, 'edit'])->name('comisiones.edit');
        Route::put('/comisiones/{id}', [ComisionController::class, 'update'])->name('comisiones.update');
        Route::delete('/comisiones/{id}', [ComisionController::class, 'destroy'])->name('comisiones.destroy');
    });

    // Gestión de Accesos
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        
        Route::get('/', [UsuarioController::class, 'index'])
            ->middleware('permission:usuarios.ver')->name('index');

        Route::middleware('permission:usuarios.gestionar')->group(function () {
            
            // Solicitudes y rechazos
            Route::get('/solicitudes', [UsuarioController::class, 'solicitudes'])->name('solicitudes'); 
            Route::post('/solicitudes/{user}/aprobar', [UsuarioController::class, 'aprobar'])->name('solicitudes.aprobar');
            Route::patch('/solicitudes/{user}/rechazar', [UsuarioController::class, 'rechazar'])->name('solicitudes.rechazar');
            Route::delete('/solicitudes/{user}/eliminar', [UsuarioController::class, 'eliminar'])->name('solicitudes.eliminar');

            // Actualizar roles y desactivar
            Route::put('/{user}/acceso', [UsuarioController::class, 'actualizarAcceso'])
                ->whereNumber('user')->name('acceso.actualizar');
            Route::patch('/{user}/desactivar', [UsuarioController::class, 'desactivar'])
                ->whereNumber('user')->name('desactivar');
        });
    });
});

require __DIR__.'/auth.php';