@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm border-start border-success border-4 bg-light">
            <div class="card-body">
                <h5 class="card-title text-success mb-3">
                    <i class="bi bi-flower1"></i> Konfigurasi Set Point Tanaman
                </h5>

                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="me-auto">
                        <span class="text-muted d-block">Tanaman Aktif Saat Ini:</span>
                        <h4 class="fw-bold mb-0 text-uppercase">
                            {{ $device->cropConfig->nama_tanaman ?? 'BELUM DIPILIH' }}
                        </h4>
                    </div>

                    <form action="{{ route('ubah.tanaman') }}" method="POST" class="d-flex align-items-center gap-2">
                        @csrf
                        <select name="crop_config_id" class="form-select fw-bold border-success">
                            @foreach(\App\Models\CropConfig::all() as $crop)
                            <option value="{{ $crop->id }}" {{ ($device->crop_config_id == $crop->id) ? 'selected' : '' }}>
                                {{ $crop->nama_tanaman }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-success fw-bold text-nowrap">
                            Terapkan Set Point
                        </button>
                    </form>
                </div>

                <hr>
                <div class="row text-center text-muted small">
                    <div class="col-3">Suhu: <strong>{{ $device->cropConfig->batas_bawah_suhu ?? '-' }} - {{ $device->cropConfig->batas_atas_suhu ?? '-' }}°C</strong></div>
                    <div class="col-3">pH: <strong>{{ $device->cropConfig->batas_bawah_ph ?? '-' }} - {{ $device->cropConfig->batas_atas_ph ?? '-' }}</strong></div>
                    <div class="col-3">RH: <strong>{{ $device->cropConfig->batas_bawah_kelembapan ?? '-' }} - {{ $device->cropConfig->batas_atas_kelembapan ?? '-' }}%</strong></div>
                    <div class="col-3">TDS: <strong>{{ $device->cropConfig->batas_bawah_tds ?? '-' }} - {{ $device->cropConfig->batas_atas_tds ?? '-' }} ppm</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-3 text-secondary">📊 Pemantauan Lingkungan Aktual</h4>

<div class="row mb-3">
    <div class="col-md-4 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Suhu Udara</h6>
                <h2 class="display-5 fw-bold">{{ $sensor?->suhu_udara ?? '0' }}<span class="fs-4">°C</span></h2>
                <small>Status Fuzzy: {{ $sensor?->fuzzyLog?->himpunan_suhu ?? 'Tidak Ada Data' }}</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Kelembapan Udara</h6>
                <h2 class="display-5 fw-bold">{{ $sensor?->kelembapan ?? '0' }}<span class="fs-4">%</span></h2>
                <small class="text-white-50">Dimonitor & Dikendalikan</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Tingkat pH Air</h6>
                <h2 class="display-5 fw-bold">{{ $sensor?->ph_air ?? '0' }}</h2>
                <small>Status Fuzzy: {{ $sensor?->fuzzyLog?->himpunan_ph ?? 'Tidak Ada Data' }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-6 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Konsentrasi Nutrisi (TDS)</h6>
                <h2 class="display-5 fw-bold">{{ $sensor?->tds ?? '0' }}<span class="fs-4"> ppm</span></h2>
                <small class="text-muted">Hanya Dimonitor (Tidak Dikendalikan)</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-secondary text-white h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Intensitas Cahaya</h6>
                <h2 class="display-5 fw-bold">{{ $sensor?->intensitas_cahaya ?? '0' }}<span class="fs-4"> lux</span></h2>
                <small>Status Cahaya: {{ $sensor?->fuzzyLog?->himpunan_cahaya ?? 'Tidak Ada Data' }}</small>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3 mt-5">
    <h4 class="text-secondary mb-0">🎛️ Panel Kendali Pengawas (Override)</h4>
    <div id="mode-badge-container">
        @if(($device->mode_sistem ?? 'AUTO') === 'MANUAL')
        <span class="badge bg-danger p-2 fs-6 shadow-sm" id="status-mode">MODE SISTEM: MANUAL</span>
        @else
        <span class="badge bg-success p-2 fs-6 shadow-sm" id="status-mode">MODE SISTEM: AUTO</span>
        @endif
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body text-center">
                <h5 class="card-title mb-4">Misting</h5>
                <div class="d-grid gap-2">
                    <button id="btn-misting-ON" class="btn {{ ($device->misting ?? 'OFF') === 'ON' ? 'btn-success' : 'btn-outline-success' }} fw-bold" onclick="kirimPerintah('misting', 'ON')">NYALAKAN (ON)</button>
                    <button id="btn-misting-OFF" class="btn {{ ($device->misting ?? 'OFF') === 'OFF' ? 'btn-danger' : 'btn-outline-danger' }} fw-bold" onclick="kirimPerintah('misting', 'OFF')">MATIKAN (OFF)</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body text-center">
                <h5 class="card-title mb-4">Pompa pH Up</h5>
                <div class="d-grid gap-2">
                    <button id="btn-pompa_ph_up-ON" class="btn {{ ($device->pompa_ph_up ?? 'OFF') === 'ON' ? 'btn-success' : 'btn-outline-success' }} fw-bold" onclick="kirimPerintah('pompa_ph_up', 'ON')">NYALAKAN (ON)</button>
                    <button id="btn-pompa_ph_up-OFF" class="btn {{ ($device->pompa_ph_up ?? 'OFF') === 'OFF' ? 'btn-danger' : 'btn-outline-danger' }} fw-bold" onclick="kirimPerintah('pompa_ph_up', 'OFF')">MATIKAN (OFF)</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body text-center">
                <h5 class="card-title mb-4">Pompa pH Down</h5>
                <div class="d-grid gap-2">
                    <button id="btn-pompa_ph_down-ON" class="btn {{ ($device->pompa_ph_down ?? 'OFF') === 'ON' ? 'btn-success' : 'btn-outline-success' }} fw-bold" onclick="kirimPerintah('pompa_ph_down', 'ON')">NYALAKAN (ON)</button>
                    <button id="btn-pompa_ph_down-OFF" class="btn {{ ($device->pompa_ph_down ?? 'OFF') === 'OFF' ? 'btn-danger' : 'btn-outline-danger' }} fw-bold" onclick="kirimPerintah('pompa_ph_down', 'OFF')">MATIKAN (OFF)</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body text-center">
                <h5 class="card-title mb-4">Lampu Growlight</h5>
                <div class="d-grid gap-2">
                    <button id="btn-growlight-ON" class="btn {{ ($device->growlight ?? 'OFF') === 'ON' ? 'btn-success' : 'btn-outline-success' }} fw-bold" onclick="kirimPerintah('growlight', 'ON')">NYALAKAN (ON)</button>
                    <button id="btn-growlight-OFF" class="btn {{ ($device->growlight ?? 'OFF') === 'OFF' ? 'btn-danger' : 'btn-outline-danger' }} fw-bold" onclick="kirimPerintah('growlight', 'OFF')">MATIKAN (OFF)</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function kirimPerintah(namaAktuator, aksi) {
        let yakin = confirm(`Apakah Anda yakin ingin mengubah ${namaAktuator} menjadi ${aksi}? Sistem akan beralih ke Mode MANUAL.`);

        if (!yakin) {
            return;
        }

        let payload = {
            device_id: "ALAT_HIDROPONIK_01", // PERBAIKAN: Dari HODROPONIK jadi HIDROPONIK
            nama_aktuator: namaAktuator,
            status_aksi: aksi,
            mode_sistem: "MANUAL",
            trigger_source: "USER_WEB"
        };

        fetch('/api/kontrol/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ BERHASIL: ' + data.message);
                    location.reload();
                } else {
                    alert('❌ GAGAL: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error); // PERBAIKAN: Sintaks penulisan console log yang keliru
                alert('⚠️ Terjadi kesalahan saat menghubungi server.');
            });
    }
</script>
@endsection