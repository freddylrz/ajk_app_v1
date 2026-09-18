@extends('layouts.app')

@section('content')

<div class="pct-body">
<div class="card">
    <div class="card-header">
        <h3>Rekap Penutupan</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive dt-responsive">
            <table class="table table-striped table-bordered nowrap" id="table-klaim" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Deklarasi</th>
                        <th>No. Polis</th>
                        <th>Debitur</th>
                        <th>NIK</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Plafond Kredit</th>
                        <th>Tanggal Dibuat</th>
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
