<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    // Daftar kolom yang boleh diisi (sesuaikan dengan migration)
    protected $fillable = [
        'ph_air',
        'suhu_udara',
        'kelembapan',
        'tds',
        'intensitas_cahaya'
    ];

    // Relasi: Satu data sensor punya satu data fuzzy
    public function fuzzyLog()
    {
        return $this->hasOne(FuzzyLog::class);
    }
}
