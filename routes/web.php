<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// memanggil fungsi index di DashboardController pakai alamat utama (/)
Route::get('/', [DashboardController::class, 'index']);
