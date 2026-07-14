@extends('layouts.app')

@section('content')
    <style>
        #tableHead>tr>th {
            text-align: center !important;
        }
    </style>
    <div class="pct-body">
        <div class="card">
            <div class="card-header">
                <h3><span class="m-l-10" id="pageTitle">List Penutupan</span></h3>
            </div>

            <div class="card-body">
                <table class="table table-striped" id="table-penutupan" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Registrasi</th>
                            <th>Debitur</th>
                            <th>NIK</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Nama Instansi</th>
                            <th>Plafond Kredit</th>
                            <th>Status <br><small>(klik untuk detail)</small></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    {{-- <tr>
                        <td></td>
                        <td colspan="6" class="text-start">TOTAL</td>
                        <td id="total-plafond" class="text-end">-</td>
                        <td></td>
                    </tr> --}}
                </table>
            </div>
        </div>
    </div>
    @push('levelPluginsJsHeader')
        <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    @endpush

    @push('levelPluginsJs')
        <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        @vite(['resources/js/tib/penutupan/list.js'])
    @endpush
@endsection
