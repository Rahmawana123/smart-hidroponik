<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceStatus;
use App\Models\ActuatorLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Pustaka bawaan Laravel untuk manipulasi waktu

class ControlController extends Controller
{
    public function updateStatus(Request $request)
    {
        // memvalidasi perintah yang masuk supaya nama aktuator hanya boleh 4 alat ini aja
        $request->validate([
            'device_id' => 'required|string',
            'nama_aktuator' => 'required|in:pompa_ph_up,pompa_ph_down,misting,growlight',
            'status_aksi' => 'required|in:ON,OFF',
            'mode_sistem' => 'required|in:AUTO,MANUAL',
            'trigger_source' => 'required|string' // WEB atau SISTEM FUZZY nya
        ]);

        DB::beginTransaction();
        try {
            //  cari data alat berdasarkan device_id. Jika belum ada buat baru
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

            // ubah status aktuator sesuai nama yang dikirim dari web
            $nama_aktuator = $request->nama_aktuator;
            $device->$nama_aktuator = $request->status_aksi;
            $device->mode_sistem = $request->mode_sistem;

            // Supervisory Control 
            if ($request->mode_sistem === 'MANUAL') {
                // jika web mengambil alih, beri waktu 10 menit.
                // jika dalam 10 menit nggak ada perintah baru, nanti sistem bisa kembali ke AUTO (manual)
                $device->override_until = Carbon::now()->addMinutes(10);
            } else {
                // kalauu dibalikkan ke AUTO, kosongkan batas waktu
                $device->override_until = null;
            }

            $device->save(); // simpan perubahan status alat

            // Catat ke tabel actuator_logs
            ActuatorLog::create([
                'nama_aktuator' => $request->nama_aktuator,
                'status' => $request->status_aksi,
                'trigger_source' => $request->trigger_source,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Supervisory command diterima: {$nama_aktuator} menjadi {$request->status_aksi}",
                'data' => $device
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengeksekusi perintah kendali: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getSetPoint($device_id)
    {
        // Cari alat beserta konfigurasi tanaman yang aktif
        $device = \App\Models\DeviceStatus::with('cropConfig')
            ->where('device_id', $device_id)
            ->first();

        // Jika alat tidak ditemukan
        if (!$device) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alat tidak ditemukan'
            ], 404);
        }

        // Ambil konfigurasi tanaman yang sedang dipilih
        $config = $device->cropConfig;

        // Jika belum ada tanaman yang dipilih
        if (!$config) {
            return response()->json([
                'status' => 'error',
                'message' => 'Belum ada tanaman yang dipilih pada dashboard'
            ], 404);
        }

        // Kirim seluruh set point ke ESP32
        return response()->json([
            'status' => 'success',
            'message' => 'Set point aktif berhasil diambil',
            'device_id' => $device->device_id,
            'mode_sistem' => $device->mode_sistem,
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

                // TDS (untuk monitoring dan notifikasi)
                'batas_bawah_tds' => $config->batas_bawah_tds,
                'batas_atas_tds' => $config->batas_atas_tds,
            ]
        ], 200);
    }
}
