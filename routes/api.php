<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\ControlController;

// endpoint untuk alat mengirim data sensor dan fuzzy ke web nya
Route::post('/sensor/kirim', [SensorController::class, 'store']);

// endpoint untuk web/alat mengubah status mode dan aktuator
Route::post('/kontrol/update', [ControlController::class, 'updateStatus']);

// API untuk ESP32 mengambil nilai target (Set Point) tanaman
Route::get('/config/{device_id}', [\App\Http\Controllers\Api\ControlController::class, 'getSetPoint']);
