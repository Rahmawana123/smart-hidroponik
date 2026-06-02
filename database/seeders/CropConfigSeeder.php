<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CropConfig; // Jangan lupa panggil modelnya

class CropConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Resep 1: Selada (Bisa Anda ganti angkanya kapan saja nanti)
        CropConfig::create([
            'nama_tanaman' => 'Selada',
            'batas_bawah_ph' => 6.0,
            'batas_atas_ph' => 7.0,
            'batas_bawah_suhu' => 15.0,
            'batas_atas_suhu' => 25.0,
            'batas_bawah_kelembapan' => 60.0,
            'batas_atas_kelembapan' => 80.0,
            'batas_bawah_cahaya' => 150,
            'batas_atas_cahaya' => 800,
            'batas_bawah_tds' => 560,
            'batas_atas_tds' => 840
        ]);

        // Resep 2: Pakcoy
        CropConfig::create([
            'nama_tanaman' => 'Pakcoy',
            'batas_bawah_ph' => 6.5,
            'batas_atas_ph' => 7.0,
            'batas_bawah_suhu' => 20.0,
            'batas_atas_suhu' => 30.0,
            'batas_bawah_kelembapan' => 60.0,
            'batas_atas_kelembapan' => 75.0,
            'batas_bawah_cahaya' => 200,
            'batas_atas_cahaya' => 1000,
            'batas_bawah_tds' => 1050,
            'batas_atas_tds' => 1400
        ]);

        // Anda bisa menambahkan Resep 3 (Kangkung), Resep 4 (Bayam), dst di sini nanti

        \App\Models\DeviceStatus::create([
            'device_id' => 'ALAT_HIDROPONIK_01',
            'mode_sistem' => 'AUTO',
            'crop_config_id' => 1
        ]);
    }
}
