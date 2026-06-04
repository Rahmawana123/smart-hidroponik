@extends('layouts.app')

@section('title', 'Riwayat & Grafik Tren')

@section('content')

<div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 15px;">
    <div class="card-body p-4 text-white" style="
        background: linear-gradient(rgba(20, 50, 25, 0.8), rgba(20, 50, 25, 0.8)), 
        url('{{ asset('images/bg6.jpeg') }}') no-repeat center center; 
        background-size: cover;">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold mb-1"><i class="bi bi-clock-history"></i> Riwayat & Tren Data</h4>
                <p class="mb-0 text-white-50 small">Analisis parameter lingkungan dan log sistem secara historis</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('export.excel') }}" class="btn btn-success fw-bold btn-sm shadow-sm border border-light text-nowrap">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
                <a href="{{ route('history') }}" class="btn btn-sm btn-outline-light text-nowrap">
                    <i class="bi bi-arrow-clockwise"></i> Segarkan Data
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
            <div class="card-body">
                <h6 class="card-title text-muted fw-bold mb-3"><i class="bi bi-graph-up-arrow text-info me-2"></i>Tren Mikroklimat (Suhu & Kelembapan)</h6>
                <div style="position: relative; height:250px;">
                    <canvas id="chartMikroklimat"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
            <div class="card-body">
                <h6 class="card-title text-muted fw-bold mb-3"><i class="bi bi-droplet-half text-success me-2"></i>Tren Kualitas Air (pH Air)</h6>
                <div style="position: relative; height:250px;">
                    <canvas id="chartAir"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center mb-3">
    <h4 class="text-secondary mb-0"><i class="bi bi-table me-2"></i>Log Data Sensor Keseluruhan</h4>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3">Waktu Rekam</th>
                        <th class="py-3">Suhu Udara</th>
                        <th class="py-3">Status Suhu</th>
                        <th class="py-3">Kelembapan</th>
                        <th class="py-3">Status Kelembapan</th>
                        <th class="py-3">pH Air</th>
                        <th class="py-3">Status pH</th>
                        <th class="py-3">TDS (Nutrisi)</th>
                        <th class="py-3">Cahaya</th>
                        <th class="py-3">Status Cahaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyData as $data)
                    <tr>
                        <td class="ps-4 fw-semibold text-muted" style="font-size: 0.9rem;">
                            {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') }}
                        </td>
                        <td class="fw-bold">{{ $data->suhu_udara }} °C</td>
                        <td>
                            <span class="badge bg-{{ ($data->fuzzyLog->himpunan_suhu ?? '') === 'PANAS' ? 'danger' : 'success' }}">
                                {{ $data->fuzzyLog->himpunan_suhu ?? '-' }}
                            </span>
                        </td>
                        <td class="fw-bold">{{ $data->kelembapan }} %</td>
                        <td>
                            <span class="badge bg-{{ ($data->fuzzyLog->himpunan_kelembapan ?? '') === 'KERING' ? 'warning text-dark' : (($data->fuzzyLog->himpunan_kelembapan ?? '') === 'LEMBAP' ? 'info text-dark' : 'success') }}">
                                {{ $data->fuzzyLog->himpunan_kelembapan ?? '-' }}
                            </span>
                        </td>
                        <td class="fw-bold text-success">{{ $data->ph_air }}</td>
                        <td>
                            <span class="badge bg-{{ ($data->fuzzyLog->himpunan_ph ?? '') === 'NORMAL' ? 'success' : 'warning text-dark' }}">
                                {{ $data->fuzzyLog->himpunan_ph ?? '-' }}
                            </span>
                        </td>
                        <td class="fw-bold text-warning">{{ $data->tds }} ppm</td>
                        <td class="fw-bold text-secondary">{{ $data->intensitas_cahaya }} lux</td>
                        <td>
                            <span class="badge bg-{{ ($data->fuzzyLog->himpunan_cahaya ?? '') === 'NORMAL' ? 'success' : (($data->fuzzyLog->himpunan_cahaya ?? '') === 'REDUP' ? 'secondary' : 'warning text-dark') }}">
                                {{ $data->fuzzyLog->himpunan_cahaya ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center p-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-black-50"></i>
                            Belum ada rekam jejak data sensor di database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mb-5">
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
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Kelembapan (%)',
                    data: dataKelembapan,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
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
                }
            }
        }
    });
</script>
@endsection