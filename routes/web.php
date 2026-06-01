<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;

// memanggil fungsi index di DashboardController pakai alamat utama (/)
Route::get('/', [DashboardController::class, 'index']);

Route::get('/riwayat', [HistoryController::class, 'index'])->name('history');
