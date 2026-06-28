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
        'crop_config_id'
    ];

    public function cropConfig()
    {
        return $this->belongsTo(CropConfig::class, 'crop_config_id');
    }
}
