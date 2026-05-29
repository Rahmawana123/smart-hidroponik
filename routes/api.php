<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\ControlController;

// Endpoint untuk alat mengirim data sensor & fuzzy ke web
Route::post('/sensor/kirim', [SensorController::class, 'store']);

// Endpoint untuk web/alat mengubah status mode dan aktuator
Route::post('/kontrol/update', [ControlController::class, 'updateStatus']);
