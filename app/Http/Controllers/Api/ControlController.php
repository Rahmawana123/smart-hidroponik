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
            'nama_aktuator' => 'required|in:pompa_ph_up,pompa_ph_down,kipas_mikroklimat,growlight',
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
                    'kipas_mikroklimat' => 'OFF',
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
}
