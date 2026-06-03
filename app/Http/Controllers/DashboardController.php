<?php

namespace App\Http\Controllers;

use App\Models\DeviceStatus;
use App\Models\SensorReading;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sensor = SensorReading::with('fuzzyLog')->latest()->first();

        $device = DeviceStatus::where(
            'device_id',
            'ALAT_HIDROPONIK_01'
        )->first();

        return view('dashboard', compact('sensor', 'device'));
    }
}
