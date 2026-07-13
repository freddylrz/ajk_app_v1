@extends('layouts.app')

@section('pageTitle', 'Data Klaim')
@section('pageIcon', 'ti ti-files')

@section('content')

<div class="card">
    <div class="card-header">
        <i class="ti ti-table"></i>
        <h5>Data Klaim — BNI Cabang KC KUNINGAN</h5>
        <a href="{{ route('client.klaim.laporan-awal') }}" class="btn btn-warning btn-sm ms-auto">
            <i class="ti ti-plus"></i> Lapor Klaim Baru
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-klaim" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Klaim ID</th>
                        <th>Debitur</th>
                        <th>No. Polis</th>
                        <th>Cabang</th>
                        <th>Tanggal Kematian</th>
                        <th>Nilai Klaim</th>
                        <th>Tanggal Lapor</th>
                        <th>Deskripsi</th>
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
    @vite(['resources/js/client/klaim-data.js'])
@endpush
