@extends('layouts.app')

@section('title', 'Riwayat & Grafik Tren')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-secondary mb-0">📈 Analisis & Tren Parameter Lingkungan</h4>
    <a href="{{ route('history') }}" class="btn btn-sm btn-outline-secondary">🔄 Segarkan Grafik</a>
</div>

<div class="row mb-5">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted fw-bold">📉 Tren Mikroklimat (Suhu & Kelembapan)</h6>
                <div style="position: relative; height:250px;">
                    <canvas id="chartMikroklimat"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted fw-bold">📉 Tren Kualitas Air (pH Air)</h6>
                <div style="position: relative; height:250px;">
                    <canvas id="chartAir"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-3 text-secondary">📋 Log Data Sensor Keseluruhan</h4>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Waktu Rekam</th>
                        <th>Suhu Udara</th>
                        <th>Status Suhu</th>
                        <th>Kelembapan</th>
                        <th>pH Air</th>
                        <th>Status pH</th>
                        <th>TDS (Nutrisi)</th>
                        <th>Cahaya</th>
                        <th>Status Cahaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyData as $data)
                    <tr>
                        <td class="ps-3 fw-semibold text-muted">
                            {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') }}
                        </td>
                        <td>{{ $data->suhu_udara }} °C</td>
                        <td>
                            <span class="badge bg-{{ ($data->fuzzyLog->himpunan_suhu ?? '') === 'PANAS' ? 'danger' : 'success' }}">
                                {{ $data->fuzzyLog->himpunan_suhu ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $data->kelembapan }} %</td>
                        <td class="fw-bold text-success">{{ $data->ph_air }}</td>
                        <td>
                            <span class="badge bg-{{ ($data->fuzzyLog->himpunan_ph ?? '') === 'NORMAL' ? 'success' : 'warning text-dark' }}">
                                {{ $data->fuzzyLog->himpunan_ph ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $data->tds }} ppm</td>
                        <td>{{ $data->intensitas_cahaya }} lux</td>
                        <td>
                            <span class="badge bg-{{ ($data->fuzzyLog->himpunan_cahaya ?? '') === 'NORMAL' ? 'success' : (($data->fuzzyLog->himpunan_cahaya ?? '') === 'REDUP' ? 'secondary' : 'warning text-dark') }}">
                                {{ $data->fuzzyLog->himpunan_cahaya ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-4 text-muted">Belum ada rekam jejak data sensor di database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end">
    {{ $historyData->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labelWaktu = @json($labelWaktu);
    const dataSuhu = @json($dataSuhu);
    const dataKelembapan = @json($dataKelembapan);
    const dataPH = @json($dataPH);

    // 2. Render Grafik Mikroklimat (Suhu & Kelembapan)
    const ctxMikro = document.getElementById('chartMikroklimat').getContext('2d');
    new Chart(ctxMikro, {
        type: 'line',
        data: {
            labels: labelWaktu,
            datasets: [{
                    label: 'Suhu Udara (°C)',
                    data: dataSuhu,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Kelembapan (%)',
                    data: dataKelembapan,
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });

    // 3. Render Grafik Kualitas Air (pH)
    const ctxAir = document.getElementById('chartAir').getContext('2d');
    new Chart(ctxAir, {
        type: 'line',
        data: {
            labels: labelWaktu,
            datasets: [{
                label: 'Tingkat pH Air',
                data: dataPH,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 14
                } // pH berkisar antara skala logaritma 0 s.d 14
            }
        }
    });
</script>
@endsection