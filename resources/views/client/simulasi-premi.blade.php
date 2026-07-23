@extends('layouts.app')

@section('pageTitle', 'Simulasi Hitung Premi')

@section('content')

<div class="pct-body">
    <div class="card">
        <div class="card-header">
            <h3>Simulasi Hitung Premi</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="tanggal_lahir"
                                   placeholder="dd-mm-yyyy" required>
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Periode Awal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="periode_awal"
                                   placeholder="dd-mm-yyyy" required>
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tenor <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="tenor" placeholder="Tenor" min="1" required>
                            <span class="input-group-text">Bulan</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Plafond Kredit <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control text-end" id="plafond_kredit"
                                   placeholder="Plafond Kredit" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-3">
                <button type="button" class="btn btn-info" id="btn-hitung">
                    <i class="ti ti-calculator"></i> Hitung
                </button>
            </div>

            <hr>

            <h5 class="mb-3">Hasil :</h5>
            <div class="row">
                <div class="col-md-3">
                    <small class="text-muted d-block">Umur</small>
                    <strong id="output_umur">-</strong>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Periode</small>
                    <strong id="output_periode">-</strong>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Rate</small>
                    <strong id="output_rate">-</strong>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Premi</small>
                    <strong id="output_premi">-</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('levelPluginsJs')
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    @vite(['resources/js/client/simulasi-premi/index.js'])
@endpush
