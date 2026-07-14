@extends('layouts.app')

@section('pageTitle', 'Beranda')
@section('pageIcon', 'ti ti-home')

@section('content')

<div class="pct-body">
{{-- ── Kartu ringkasan (pola social-widget-card, sama seperti dashboard admin) ── --}}
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="card social-widget-card bg-primary">
            <div class="card-body">
                <h3 class="text-white m-0" id="stat-pertanggungan">-</h3>
                <span class="m-t-10">Total Nilai Pertanggungan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="card social-widget-card bg-success">
            <div class="card-body">
                <h3 class="text-white m-0" id="stat-premi">-</h3>
                <span class="m-t-10">Total Premi</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="card social-widget-card bg-secondary">
            <div class="card-body">
                <h3 class="text-white m-0" id="stat-debitur">-</h3>
                <span class="m-t-10">Total Debitur</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="card social-widget-card bg-danger">
            <div class="card-body">
                <h3 class="text-white m-0" id="stat-klaim">-</h3>
                <span class="m-t-10">Total Klaim</span>
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
                <h3>Penutupan Per Bulan {{ date('Y') }}</h3>
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
                <h3>Akses Cepat</h3>
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
        <h3>Klaim Terbaru</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive dt-responsive">
            <table class="table table-striped table-bordered nowrap" id="table-klaim-terbaru" style="width:100%">
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
</div>

@endsection

@push('levelPluginsJs')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    @vite(['resources/js/client/dashboard.js'])
@endpush
