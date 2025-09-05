<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeviceController;

// Rotas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');

// Rotas de configuração de dispositivo
Route::get('/device/config', [DeviceController::class, 'config'])->name('device.config');
Route::post('/device/save', [DeviceController::class, 'save'])->name('device.save');
