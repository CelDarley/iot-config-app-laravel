<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeviceController;

// Rotas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');

// Rotas de verificação de dispositivo
Route::get('/api/device-status', [HomeController::class, 'getDeviceStatus'])->name('device.status');
Route::get('/api/check-device', [HomeController::class, 'checkDeviceConnection'])->name('device.check');

// Rotas de configuração de dispositivo
Route::get('/device/config', [DeviceController::class, 'config'])->name('device.config');
Route::get('/device/add', [DeviceController::class, 'add'])->name('device.add');
Route::get('/device/success', [DeviceController::class, 'success'])->name('device.success');
Route::get('/device/transition', [DeviceController::class, 'transition'])->name('device.transition');
Route::get('/device/transition-debug', function(Request $request) {
    $macAddress = $request->get('mac');
    $ssid = $request->get('ssid');
    return view('device.transition-debug', compact('macAddress', 'ssid'));
})->name('device.transition-debug');
Route::post('/device/save', [DeviceController::class, 'save'])->name('device.save');
Route::post('/device/save-topic', [DeviceController::class, 'saveTopic'])->name('device.save-topic');

// APIs para buscar listas
Route::get('/api/device-types', [DeviceController::class, 'getDeviceTypes'])->name('api.device-types');
Route::get('/api/departments', [DeviceController::class, 'getDepartments'])->name('api.departments');
