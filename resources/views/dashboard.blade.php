@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')
<h4 class="mb-3 text-secondary">📊 Pemantauan Lingkungan Aktual</h4>

<div class="row mb-3">
    <div class="col-md-4 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Suhu Udara</h6>
                <h2 class="display-5 fw-bold">32.5<span class="fs-4">°C</span></h2>
                <small>Status Fuzzy: PANAS</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Kelembapan Udara</h6>
                <h2 class="display-5 fw-bold">70<span class="fs-4">%</span></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Tingkat pH Air</h6>
                <h2 class="display-5 fw-bold">6.5</h2>
                <small>Status Fuzzy: NORMAL</small>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-6 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Konsentrasi Nutrisi (TDS)</h6>
                <h2 class="display-5 fw-bold">850<span class="fs-4"> ppm</span></h2>
                <small>Hanya Dimonitor (Tidak Dikendalikan)</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 mb-3">
        <div class="card border-0 shadow-sm bg-secondary text-white h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase">Intensitas Cahaya</h6>
                <h2 class="display-5 fw-bold">450<span class="fs-4"> lux</span></h2>
                <small>Hanya Dimonitor (Tidak Dikendalikan)</small>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3 mt-5">
    <h4 class="text-secondary mb-0">🎛️ Panel Kendali Pengawas (Override)</h4>
    <div>
        <span class="badge bg-danger p-2 fs-6 shadow-sm">MODE SISTEM: MANUAL</span>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body text-center">
                <h5 class="card-title mb-4">Kipas Mikroklimat</h5>
                <div class="d-grid gap-2">
                    <button class="btn btn-success fw-bold">NYALAKAN (ON)</button>
                    <button class="btn btn-outline-danger fw-bold">MATIKAN (OFF)</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body text-center">
                <h5 class="card-title mb-4">Pompa pH Up</h5>
                <div class="d-grid gap-2">
                    <button class="btn btn-success fw-bold">NYALAKAN (ON)</button>
                    <button class="btn btn-outline-danger fw-bold">MATIKAN (OFF)</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body text-center">
                <h5 class="card-title mb-4">Pompa pH Down</h5>
                <div class="d-grid gap-2">
                    <button class="btn btn-success fw-bold">NYALAKAN (ON)</button>
                    <button class="btn btn-outline-danger fw-bold">MATIKAN (OFF)</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection