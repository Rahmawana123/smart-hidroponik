<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    // daftar kolom yang boleh diisi 
    protected $fillable = [
        'ph_air',
        'suhu_udara',
        'kelembapan',
        'tds',
        'intensitas_cahaya'
    ];

    // relasinya Satu data sensor punya satu data fuzzy
    public function fuzzyLog()
    {
        return $this->hasOne(FuzzyLog::class);
    }
}
