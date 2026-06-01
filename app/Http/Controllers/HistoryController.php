<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorReading;

class HistoryController extends Controller
{
    public function index()
    {
        // 1. Data untuk Tabel (Tetap sama)
        $historyData = SensorReading::with('fuzzyLog')
            ->latest()
            ->paginate(10);

        // 2. Ambil 20 data mentah untuk grafik
        $chartDataRaw = SensorReading::latest()
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        // 3. Ekstraksi Data Grafik di Controller (Agar Blade tidak kena error spasi VS Code)
        $labelWaktu = $chartDataRaw->map(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('H:i:s');
        });
        $dataSuhu = $chartDataRaw->pluck('suhu_udara');
        $dataKelembapan = $chartDataRaw->pluck('kelembapan');
        $dataPH = $chartDataRaw->pluck('ph_air');

        // 4. Kirim semua variabel yang sudah matang ke View
        return view('riwayat', compact('historyData', 'labelWaktu', 'dataSuhu', 'dataKelembapan', 'dataPH'));
    }
}
