@extends('layouts.app')

@section('pageTitle', 'List Data Penutupan')
@section('pageIcon', 'ti ti-list-details')

@section('content')

<div class="card">
    <div class="card-header">
        <i class="ti ti-table"></i>
        <h5>Data Penutupan — BNI Cabang KC KUNINGAN</h5>
        <a href="{{ route('client.penutupan.input') }}" class="btn btn-warning btn-sm ms-auto">
            <i class="ti ti-plus"></i> Input Data Baru
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-penutupan" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Debitur</th>
                        <th>Tanggal Lahir</th>
                        <th>Institusi</th>
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

@endsection

@push('pageScripts')
    <script src="{{ asset('assets/js/client/penutupan-list.js') }}"></script>
@endpush
