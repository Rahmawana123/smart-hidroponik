<?php

namespace App\Http\Controllers\Api;

use App\Services\TelegramService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\FuzzyLog;
use Illuminate\Support\Facades\DB;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // Validasi JSON Bercabang menggunakan tanda titik
        $request->validate([
            'data_sensor.ph_air' => 'required|numeric',
            'data_sensor.suhu_udara' => 'required|numeric',
            'data_sensor.kelembapan' => 'required|numeric',
            'data_sensor.tds' => 'required|numeric',
            'data_sensor.intensitas_cahaya' => 'required|numeric',

            'data_fuzzy.himpunan_ph' => 'required|string',
            'data_fuzzy.himpunan_suhu' => 'required|string',
            'data_fuzzy.himpunan_kelembapan' => 'required|string',
            'data_fuzzy.himpunan_cahaya' => 'required|string',
            'data_fuzzy.nilai_defuzz_pompa_ph' => 'required|numeric',
            'data_fuzzy.nilai_defuzz_misting' => 'required|numeric',
            'data_fuzzy.nilai_defuzz_growlight' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            // simpan ke tabel sensor_readings  
            $sensor = SensorReading::create([
                'ph_air' => $request->input('data_sensor.ph_air'),
                'suhu_udara' => $request->input('data_sensor.suhu_udara'),
                'kelembapan' => $request->input('data_sensor.kelembapan'),
                'tds' => $request->input('data_sensor.tds'),
                'intensitas_cahaya' => $request->input('data_sensor.intensitas_cahaya'),
            ]);

            // simpan ke tabel fuzzy_logs
            FuzzyLog::create([
                'sensor_reading_id' => $sensor->id,
                'himpunan_ph' => $request->input('data_fuzzy.himpunan_ph'),
                'himpunan_suhu' => $request->input('data_fuzzy.himpunan_suhu'),
                'himpunan_kelembapan' => $request->input('data_fuzzy.himpunan_kelembapan'),
                'himpunan_cahaya' => $request->input('data_fuzzy.himpunan_cahaya'),
                'nilai_defuzz_pompa_ph' => $request->input('data_fuzzy.nilai_defuzz_pompa_ph'),
                'nilai_defuzz_misting' => $request->input('data_fuzzy.nilai_defuzz_misting'),
                'nilai_defuzz_growlight' => $request->input('data_fuzzy.nilai_defuzz_growlight'),
            ]);

            DB::commit();
            $suhu = $request->input('data_sensor.suhu_udara');
            $kelembapan = $request->input('data_sensor.kelembapan');
            $phAir = $request->input('data_sensor.ph_air');
            $tds = $request->input('data_sensor.tds');
            $cahaya = $request->input('data_sensor.intensitas_cahaya');

            // 1. Tambahkan pengambilan variabel himpunan_kelembapan
            $statusSuhu = $request->input('data_fuzzy.himpunan_suhu');
            $statusPh = $request->input('data_fuzzy.himpunan_ph');
            $statusCahaya = $request->input('data_fuzzy.himpunan_cahaya');
            $statusKelembapan = $request->input('data_fuzzy.himpunan_kelembapan');

            // 2. Tambahkan kelembapan ke dalam syarat pemicu alarm (opsional, tapi disarankan)
            if ($statusSuhu !== 'NORMAL' || $statusPh !== 'NORMAL' || $statusKelembapan !== 'NORMAL') {
                $pesanTelegram  = "🚨 *PERINGATAN DINI HIDROPONIK* 🚨\n\n";

                $pesanTelegram .= "Sistem mendeteksi parameter lingkungan berada di luar kondisi normal.\n\n";

                $pesanTelegram .= "📊 *Data Monitoring Saat Ini*\n";
                $pesanTelegram .= "🌡️ Suhu Udara : {$suhu} °C\n";
                $pesanTelegram .= "☁️ Kelembapan : {$kelembapan} %\n";
                $pesanTelegram .= "💧 pH Air : {$phAir}\n";
                $pesanTelegram .= "🧪 TDS Nutrisi : {$tds} ppm\n";
                $pesanTelegram .= "💡 Intensitas Cahaya : {$cahaya} lux\n\n";

                $pesanTelegram .= "📌 *Status Hasil Fuzzy*\n";
                $pesanTelegram .= "• Suhu : {$statusSuhu}\n";
                $pesanTelegram .= "• Kelembapan : {$statusKelembapan}\n"; // <-- 3. Tambahkan baris ini ke teks pesan
                $pesanTelegram .= "• pH Air : {$statusPh}\n";
                $pesanTelegram .= "• Cahaya : {$statusCahaya}\n\n";

                $pesanTelegram .= "🕒 Waktu : " . now()->format('d-m-Y H:i:s') . " WIB\n\n";
                $pesanTelegram .= "⚠️ Mohon segera melakukan pengecekan pada sistem hidroponik melalui dashboard monitoring.";

                TelegramService::sendMessage($pesanTelegram);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data sensor dan fuzzy berhasil disimpan',
                'data_id' => $sensor->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
