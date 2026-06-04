<?php

namespace App\Exports;

use App\Models\SensorReading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SensorLogExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private $rowNumber = 0;

    //mengambil seluruh data sensor beserta relasi log fuzzynya
    public function collection()
    {
        return SensorReading::with('fuzzyLog')
            ->orderBy('created_at', 'desc')
            ->take(500) //data yang di ambil 500 data terbaru saja
            ->get();
    }

    //membuat baris judul (Header) paling atas di Excel

    public function headings(): array
    {
        return [
            'No',
            'Waktu Record',
            'Suhu Udara (°C)',
            'Kelembapan (%)',
            'pH Air',
            'Nutrisi (TDS - ppm)',
            'Intensitas (Lux)',

            'Himpunan Suhu',
            'Himpunan Kelembapan',
            'Himpunan pH',
            'Himpunan Cahaya',

            'Defuzz Misting',
            'Defuzz Pompa pH',
            'Defuzz Growlight'
        ];
    }

    //memetakan data dari database ke kolom Excel yang sesuai
    public function map($sensor): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $sensor->created_at->format('Y-m-d H:i:s'),
            $sensor->suhu_udara,
            $sensor->kelembapan,
            $sensor->ph_air,
            $sensor->tds,
            $sensor->intensitas_cahaya,

            $sensor->fuzzyLog?->himpunan_suhu ?? '-',
            $sensor->fuzzyLog?->himpunan_kelembapan ?? '-',
            $sensor->fuzzyLog?->himpunan_ph ?? '-',
            $sensor->fuzzyLog?->himpunan_cahaya ?? '-',

            $sensor->fuzzyLog?->nilai_defuzz_misting ?? 0,
            $sensor->fuzzyLog?->nilai_defuzz_pompa_ph ?? 0,
            $sensor->fuzzyLog?->nilai_defuzz_growlight ?? 0,
        ];
    }
}
