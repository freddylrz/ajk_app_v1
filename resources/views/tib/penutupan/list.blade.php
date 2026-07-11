@extends('layouts.app')

@section('content')

<style>
    #tableHead>tr>th{
        text-align: center !important;
    }

</style>
    <div class="pct-body">
        <div class="card">
            <div class="card-header">
                <h3><i class="ti ti-list" style="font-size: 2.5rem;"></i><span class="m-l-10" id="pageTitle"></span></h3>
            </div>

            <div class="card-body">
{{--             
                <div class="table-responsive dt-responsive"> --}}
                    <table id="table" class="table table-striped table-bordered nowrap w-100">
                        <thead id="tableHead">
                            <tr>
                                <th>No</th>
                                <th>No. Registrasi</th>
                                <th>Nama</th>
                                <th>Usia</th>
                                <th>Cabang</th>
                                <th>Nilai Pertanggungan</th>
                                <th>Tenor</th>
                                <th>Periode</th>
                                <th>Premi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            {{-- </div> --}}
        </div>
    </div>
    @push('levelPluginsJsHeader')
        <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    @endpush

    @push('levelPluginsJs')
        <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        {{-- @vite(['resources/js/deklarasi/list.js']) --}}
    @endpush
@endsection