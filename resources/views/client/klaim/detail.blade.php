@extends('layouts.app')

@section('pageTitle', 'Detail Klaim')

@section('content')

<div class="pct-body">
{{-- id dikirim dari controller, dibaca oleh klaim-detail.js --}}
<div id="detail-container" data-id="{{ $id }}">

    <div class="card">
        <div class="card-header">
            <i class="ti ti-file-alert"></i>
            <h3>Klaim ID : <span id="head-klaim-id">-</span></h3>
            <span class="ms-auto" id="head-status"></span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Debitur</small>
                        <strong class="fs-5" id="d-debitur">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">No. Polis</small>
                        <strong id="d-no-polis">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Cabang</small>
                        <strong id="d-cabang">-</strong>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Tanggal Kematian</small>
                        <strong id="d-tanggal-kematian">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Tanggal Lapor</small>
                        <strong id="d-tanggal-lapor">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Deskripsi</small>
                        <strong id="d-deskripsi">-</strong>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Nilai Klaim</small>
                        <strong class="fs-5" id="d-nilai-klaim">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Dokumen Terunggah</small>
                        <ul class="list-unstyled mt-2 mb-0" id="d-dokumen"></ul>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('client.klaim.data') }}" class="btn btn-danger">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- ══ Log Status ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ti ti-timeline-event"></i>
            <h3>Log Status</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive dt-responsive">
                <table class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:70px;">No.</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th style="width:200px;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="d-log"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

@endsection

@push('levelPluginsJs')
    @vite(['resources/js/client/klaim-detail.js'])
@endpush
