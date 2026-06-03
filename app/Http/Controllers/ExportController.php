<?php

namespace App\Http\Controllers;

use App\Exports\SensorLogExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportExcel()
    {
        $fileName = 'Laporan_Smart_Hidroponik_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new SensorLogExport(),
            $fileName
        );
    }
}
