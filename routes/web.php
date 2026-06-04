<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ExportController;

// memanggil fungsi index di DashboardController pakai alamat utama (/)
Route::get('/', [DashboardController::class, 'index']);

Route::get('/riwayat', [HistoryController::class, 'index'])->name('history');


// Rute untuk mengubah jenis tanaman aktif dari Dasbor
Route::post('/ubah-tanaman', function (\Illuminate\Http\Request $request) {
    // Cari alat kita
    $device = \App\Models\DeviceStatus::where('device_id', 'ALAT_HIDROPONIK_01')->first();

    if ($device) {
        // Perbarui ID resep tanaman sesuai pilihan di dropdown
        $device->update(['crop_config_id' => $request->crop_config_id]);
    }

    // Kembalikan ke halaman dasbor
    return back()->with('success', 'Resep Set Point Tanaman berhasil diubah!');
})->name('ubah.tanaman');

// Rute untuk mengunduh data log sensor ke Excel
Route::get('/export-excel', [ExportController::class, 'exportExcel'])->name('export.excel');

Route::get('/export-pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
