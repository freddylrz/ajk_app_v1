@extends('layouts.app')

@section('content')

<div class="pct-body">
    <style>
        #tableHead tr th {
            text-align: center !important;
        }
    </style>
<div class="card">
    <div class="card-header">
        <h3>Penutupan Dalam Proses</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive dt-responsive">
            <table class="table table-striped table-bordered nowrap" id="table-penutupan" style="width:100%">
                <thead id="tableHead">
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
    @vite(['resources/js/client/penutupan/list-data.js'])
@endpush
