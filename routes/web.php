<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\ActividadController;
use App\Models\Actividad;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
        Route::prefix('usuarios')->name('usuarios.')->group(function () {
            Route::get('/', [UsuarioController::class, 'index'])->name('index');

            Route::group([],function () {
                Route::get('/solicitudes', [UsuarioController::class, 'solicitudes'])->name('solicitudes'); 
                Route::post('/solicitudes/{user}/aprobar', [UsuarioController::class, 'aprobar'])->name('solicitudes.aprobar');
                Route::patch('/solicitudes/{user}/rechazar', [UsuarioController::class, 'rechazar'])->name('solicitudes.rechazar');
                Route::delete('/solicitudes/{user}/eliminar', [UsuarioController::class, 'eliminar'])->name('solicitudes.eliminar');
        });
    });
});

Route::middleware('auth')->group(function () {
    // perfil 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //comisiones
    Route::get('/comisiones', [ComisionController::class, 'index'])->name('comisiones.index');
    Route::get('/comisiones/crear', [ComisionController::class, 'create'])->name('comisiones.create');
    Route::post('/comisiones', [ComisionController::class, 'store'])->name('comisiones.store');
    Route::post('/comisiones/ajax', [ComisionController::class, 'storeAjax'])->name('comisiones.ajax');
    Route::put('/comisiones/{id}', [ComisionController::class, 'update'])->name('comisiones.update');
    Route::get('/comisiones/{id}/editar', [ComisionController::class, 'edit'])->name('comisiones.edit');
    Route::delete('/comisiones/{id}', [ComisionController::class, 'destroy'])->name('comisiones.destroy');
    //actividades
    Route::get('/actividades', [ActividadController::class, 'index'])->name('actividades.index');
    Route::get('/actividades/crear', [ActividadController::class, 'create'])->name('actividades.create');
    Route::post('/actividades', [ActividadController::class, 'store'])->name('actividades.store');
    Route::get('/actividades/historial', [ActividadController::class, 'historial'])->name('actividades.historial');

    Route::prefix('/actividades/{actividad}')->whereNumber('actividad')->group(function () {
        Route::get('/', [ActividadController::class, 'show'])->name('actividades.show');
        Route::get('/editar', [ActividadController::class, 'edit'])->name('actividades.edit');
        Route::put('/', [ActividadController::class, 'update'])->name('actividades.update');
        Route::delete('/', [ActividadController::class, 'destroy'])->name('actividades.destroy');
    });

});


require __DIR__.'/auth.php';
