<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropConfig extends Model
{
    protected $fillable = [
        'nama_tanaman',
        'batas_bawah_ph',
        'batas_atas_ph',
        'batas_bawah_suhu',
        'batas_atas_suhu',
        'batas_bawah_kelembapan',
        'batas_atas_kelembapan',
        'batas_bawah_cahaya',
        'batas_atas_cahaya',
        'batas_bawah_tds',
        'batas_atas_tds'
    ];
}
