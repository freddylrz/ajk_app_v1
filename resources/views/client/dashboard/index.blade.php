@extends('layouts.app')

@section('pageTitle', 'Beranda')
@section('pageIcon', 'ti ti-home')

@section('content')

{{-- ── Kartu ringkasan ── --}}
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div>
                    <div class="stat-label">Total Nilai Pertanggungan</div>
                    <div class="stat-value" id="stat-pertanggungan">-</div>
                    <div class="stat-sub">Seluruh polis aktif</div>
                </div>
                <div class="stat-icon bg-green-soft"><i class="ti ti-building-bank"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div>
                    <div class="stat-label">Total Premi</div>
                    <div class="stat-value" id="stat-premi">-</div>
                    <div class="stat-sub">Premi yang sudah dibayarkan</div>
                </div>
                <div class="stat-icon bg-blue-soft"><i class="ti ti-coin"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div>
                    <div class="stat-label">Total Debitur</div>
                    <div class="stat-value" id="stat-debitur">-</div>
                    <div class="stat-sub">Peserta asuransi terdaftar</div>
                </div>
                <div class="stat-icon bg-yellow-soft"><i class="ti ti-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div>
                    <div class="stat-label">Total Klaim</div>
                    <div class="stat-value" id="stat-klaim">-</div>
                    <div class="stat-sub"><span id="stat-klaim-detail">-</span></div>
                </div>
                <div class="stat-icon bg-red-soft"><i class="ti ti-file-alert"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- ── Grafik penutupan per bulan ── --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="ti ti-chart-bar"></i>
                <h5>Penutupan Per Bulan {{ date('Y') }}</h5>
            </div>
            <div class="card-body">
                <div id="chart-penutupan"></div>
            </div>
        </div>
    </div>

    {{-- ── Akses cepat ── --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="ti ti-bolt"></i>
                <h5>Akses Cepat</h5>
            </div>
            <div class="card-body d-grid gap-3">
                <a href="{{ route('client.penutupan.input') }}" class="btn btn-primary btn-lg">
                    <i class="ti ti-file-plus"></i> Input Data Peserta Baru
                </a>
                <a href="{{ route('client.penutupan.list') }}" class="btn btn-info btn-lg">
                    <i class="ti ti-list-details"></i> Lihat List Data Penutupan
                </a>
                <a href="{{ route('client.klaim.laporan-awal') }}" class="btn btn-warning btn-lg">
                    <i class="ti ti-alert-circle"></i> Lapor Klaim Baru
                </a>
                <a href="{{ route('client.klaim.data') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="ti ti-files"></i> Lihat Data Klaim
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── Klaim terbaru ── --}}
<div class="card">
    <div class="card-header">
        <i class="ti ti-history"></i>
        <h5>Klaim Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-klaim-terbaru" style="width:100%">
                <thead>
                    <tr>
                        <th>Klaim ID</th>
                        <th>Debitur</th>
                        <th>Tanggal Lapor</th>
                        <th>Nilai Klaim</th>
                        <th>Status <small>(klik untuk detail)</small></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('pageScripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    @vite(['resources/js/client/dashboard.js'])
@endpush
