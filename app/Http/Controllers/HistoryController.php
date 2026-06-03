<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorReading;

class HistoryController extends Controller
{
    public function index()
    {

        $historyData = SensorReading::with('fuzzyLog')
            ->latest()
            ->paginate(10);

        // mengambil 20 data mentah untuk grafik
        $chartDataRaw = SensorReading::latest()
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        // mengambil jam dari setiap sensor 
        $labelWaktu = $chartDataRaw->map(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('H:i:s');
        });
        $dataSuhu = $chartDataRaw->pluck('suhu_udara');
        $dataKelembapan = $chartDataRaw->pluck('kelembapan');
        $dataPH = $chartDataRaw->pluck('ph_air');

        // kirim semua variabel yang sudah matang ke View
        return view('riwayat', compact('historyData', 'labelWaktu', 'dataSuhu', 'dataKelembapan', 'dataPH'));
    }
}
