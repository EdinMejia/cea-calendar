<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\ActividadController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/comisiones', [ComisionController::class, 'index'])->name('comisiones.index');
    Route::get('/comisiones/crear', [ComisionController::class, 'create'])->name('comisiones.create');
    Route::post('/comisiones', [ComisionController::class, 'store'])->name('comisiones.store');
    Route::post('/comisiones/ajax', [ComisionController::class, 'storeAjax'])->name('comisiones.ajax');
    Route::put('/comisiones/{id}', [ComisionController::class, 'update'])->name('comisiones.update');
    Route::get('/comisiones/{id}/editar', [ComisionController::class, 'edit'])->name('comisiones.edit');
    Route::delete('comisiones/{id}', [ComisionController::class, 'destroy'])->name('comisiones.destroy'); 
    Route::get('/actividades/crear', [ActividadController::class, 'create'])->name('actividades.create');
    Route::post('/actividades', [ActividadController::class, 'store'])->name('actividades.store');
});


require __DIR__.'/auth.php';
