<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceStatus;
use App\Models\ActuatorLog;
use Illuminate\Support\Facades\DB;

class ControlController extends Controller
{
    // FUNGSI 1: Untuk menyalakan/mematikan Aktuator
    public function updateStatus(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'device_id' => 'required|string',
            'nama_aktuator' => 'required|in:pompa_ph_up,pompa_ph_down,misting,growlight',
            'status_aksi' => 'required|in:ON,OFF',
            'trigger_source' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $device = DeviceStatus::firstOrCreate(
                ['device_id' => $request->device_id],
                [
                    'mode_sistem' => 'AUTO',
                    'pompa_ph_up' => 'OFF',
                    'pompa_ph_down' => 'OFF',
                    'misting' => 'OFF',
                    'growlight' => 'OFF'
                ]
            );

            // 2. Validasi Keamanan Backend: Tolak jika masih AUTO
            if ($device->mode_sistem !== 'MANUAL') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak! Ubah mode ke MANUAL terlebih dahulu di Dasbor.'
                ], 403);
            }

            // 3. Eksekusi Perintah
            $nama_aktuator = $request->nama_aktuator;
            $device->$nama_aktuator = $request->status_aksi;
            $device->save();

            // Catat log
            ActuatorLog::create([
                'nama_aktuator' => $request->nama_aktuator,
                'status' => $request->status_aksi,
                'trigger_source' => $request->trigger_source,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Perintah diterima: {$nama_aktuator} menjadi {$request->status_aksi}",
                'data' => $device
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengeksekusi perintah: ' . $e->getMessage()
            ], 500);
        }
    }

    // FUNGSI 2: Khusus untuk mengubah Mode Sistem (Ini yang tadi terhapus!)
    public function updateMode(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'mode_sistem' => 'required|in:AUTO,MANUAL'
        ]);

        $device = DeviceStatus::where('device_id', $request->device_id)->first();

        if (!$device) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perangkat tidak ditemukan'
            ], 404);
        }

        $device->mode_sistem = $request->mode_sistem;
        $device->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Sistem berhasil beralih ke mode ' . $request->mode_sistem,
            'data' => $device
        ], 200);
    }

    // FUNGSI 3: Untuk ESP32 Mengambil Set Point Tanaman
    public function getSetPoint($device_id)
    {
        $device = \App\Models\DeviceStatus::with('cropConfig')
            ->where('device_id', $device_id)
            ->first();

        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Alat tidak ditemukan'], 404);
        }

        $config = $device->cropConfig;

        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Belum ada tanaman yang dipilih'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Set point aktif berhasil diambil',
            'device_id' => $device->device_id,
            'mode_sistem' => $device->mode_sistem,
            'misting' => $device->misting,
            'pompa_ph_up' => $device->pompa_ph_up,
            'pompa_ph_down' => $device->pompa_ph_down,
            'growlight' => $device->growlight,
            'data' => [
                'nama_tanaman' => $config->nama_tanaman,
                'batas_bawah_ph' => $config->batas_bawah_ph,
                'batas_atas_ph' => $config->batas_atas_ph,
                'batas_bawah_suhu' => $config->batas_bawah_suhu,
                'batas_atas_suhu' => $config->batas_atas_suhu,
                'batas_bawah_kelembapan' => $config->batas_bawah_kelembapan,
                'batas_atas_kelembapan' => $config->batas_atas_kelembapan,
                'batas_bawah_cahaya' => $config->batas_bawah_cahaya,
                'batas_atas_cahaya' => $config->batas_atas_cahaya,
                'batas_bawah_tds' => $config->batas_bawah_tds,
                'batas_atas_tds' => $config->batas_atas_tds,
            ]
        ], 200);
    }
}
