<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuzzyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_reading_id',
        'himpunan_ph',
        'himpunan_suhu',
        'nilai_defuzz_pompa_ph',
        'nilai_defuzz_kipas'
    ];

    // relasi balik nya data fuzzy ini milik satu data sensor
    public function sensorReading()
    {
        return $this->belongsTo(SensorReading::class);
    }
}