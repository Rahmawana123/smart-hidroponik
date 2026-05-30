<?php

namespace App\Http\Controllers\Api;

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
            'data_fuzzy.nilai_defuzz_pompa_ph' => 'required|numeric',
            'data_fuzzy.nilai_defuzz_kipas' => 'required|numeric',
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
                'nilai_defuzz_pompa_ph' => $request->input('data_fuzzy.nilai_defuzz_pompa_ph'),
                'nilai_defuzz_kipas' => $request->input('data_fuzzy.nilai_defuzz_kipas'),
            ]);

            DB::commit();

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
