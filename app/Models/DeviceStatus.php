<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'mode_sistem',
        'pompa_ph_up',
        'pompa_ph_down',
        'misting',
        'growlight',
        'override_until',
        'crop_config_id'
    ];

    // Penting untuk kolom waktu (override_until) agar dikenali sebagai objek Carbon/Waktu
    protected $casts = [
        'override_until' => 'datetime',
    ];

    public function cropConfig()
    {
        return $this->belongsTo(CropConfig::class, 'crop_config_id');
    }
}
