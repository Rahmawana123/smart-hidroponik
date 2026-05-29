<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActuatorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_aktuator',
        'status',
        'trigger_source'
    ];
}
