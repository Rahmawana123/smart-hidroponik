<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CropConfig; // Jangan lupa panggil modelnya

class CropConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Resep 1: Selada 
        CropConfig::updateOrCreate(
            ['nama_tanaman' => 'Sawi Pakcoy (Brassica rapa L.)'],
            [
                'batas_bawah_suhu'       => 22.0,
                'batas_atas_suhu'        => 33.0,
                'batas_bawah_ph'         => 6.0,
                'batas_atas_ph'          => 7.0,
                'batas_bawah_kelembapan' => 60.0,
                'batas_atas_kelembapan'  => 80.0,
                'batas_bawah_cahaya'     => 5400.0,
                'batas_atas_cahaya'      => 10000.0,
                'batas_bawah_tds'        => 900.0,
                'batas_atas_tds'         => 1400.0,
            ]
        );

        // 2. Inisialisasi Data Opsi Tanaman Tambahan (Selada)
        CropConfig::updateOrCreate(
            ['nama_tanaman' => 'Selada (Lactuca sativa)'],
            [
                'batas_bawah_suhu'       => 25.0,
                'batas_atas_suhu'        => 28.0,
                'batas_bawah_ph'         => 6.0,
                'batas_atas_ph'          => 6.8,
                'batas_bawah_kelembapan' => 65.0,
                'batas_atas_kelembapan'  => 80.0,
                'batas_bawah_cahaya'     => 4850.0,
                'batas_atas_cahaya'      => 7890.0,
                'batas_bawah_tds'        => 700.0,
                'batas_atas_tds'         => 1000.0,
            ]
        );

        // Anda bisa menambahkan Resep 3 (Kangkung), Resep 4 (Bayam), dst di sini nanti

        \App\Models\DeviceStatus::create([
            'device_id' => 'ALAT_HIDROPONIK_01',
            'mode_sistem' => 'AUTO',
            'crop_config_id' => 1
        ]);
    }
}
