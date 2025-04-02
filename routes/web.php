<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\TankStateController;
use App\Http\Controllers\TankConfigController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [TankStateController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/create', [DeviceController::class, 'create'])->name('devices.create');
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
    Route::get('/devices/{device}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
    Route::put('/devices/{device}', [DeviceController::class, 'update'])->name('devices.update');
    Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tankconfigs', [TankConfigController::class, 'index'])->name('tankconfigs.index');
    Route::get('/tankconfigs/{tank_config}/edit', [TankConfigController::class, 'edit'])->name('tankconfigs.edit');
    Route::put('/tankconfigs/{tank_config}', [TankConfigController::class, 'update'])->name('tankconfigs.update');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
