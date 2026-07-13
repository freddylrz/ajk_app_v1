@extends('layouts.app')

@section('pageTitle', 'List Data Penutupan')

@section('content')

<div class="pct-body">
<div class="card">
    <div class="card-header">
        <i class="ti ti-table"></i>
        <h3>Data Penutupan</h3>
    </div>
    <div class="card-body">
        <a href="{{ route('client.penutupan.input') }}" class="btn btn-primary btn-sm mb-3 float-end">
            <i class="ti ti-plus"></i> Input Data Baru
        </a>
        <div class="table-responsive dt-responsive">
            <table class="table table-striped table-bordered nowrap" id="table-penutupan" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori Debitur</th>
                        <th>Debitur</th>
                        <th>Tanggal Lahir</th>
                        <th>Nama Instansi</th>
                        <th>No. PK</th>
                        <th>Tenor</th>
                        <th>Periode</th>
                        <th>Plafond Kredit</th>
                        <th>Rate Premi</th>
                        <th>Nilai Premi</th>
                        <th>Status <small>(klik untuk detail)</small></th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <td colspan="8" class="text-end">TOTAL</td>
                        <td id="total-plafond" class="text-end">-</td>
                        <td></td>
                        <td id="total-premi" class="text-end">-</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</div>

@endsection

@push('levelPluginsJs')
    @vite(['resources/js/client/penutupan-list.js'])
@endpush
