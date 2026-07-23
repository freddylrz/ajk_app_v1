@extends('layouts.app')

@section('content')

<div class="pct-body">
<div class="card">
    <div class="card-header">
        <h3>Rekap Klaim</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive dt-responsive">
            <table class="table table-striped table-bordered nowrap" id="table-klaim" style="width:100%">
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
                        <th>Status</th>
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
    @vite(['resources/js/client/klaim/data.js'])
@endpush
