@extends('layouts.app')

@section('pageTitle', 'Detail Klaim')
@section('pageIcon', 'ti ti-file-search')

@section('content')

{{-- id dikirim dari controller, dibaca oleh klaim-detail.js --}}
<div id="detail-container" data-id="{{ $id }}">

    <div class="card">
        <div class="card-header">
            <i class="ti ti-file-alert"></i>
            <h5>Klaim ID : <span id="head-klaim-id">-</span></h5>
            <span class="ms-auto" id="head-status"></span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="detail-item">
                        <div class="detail-label">Debitur</div>
                        <div class="detail-value big" id="d-debitur">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">No. Polis</div>
                        <div class="detail-value" id="d-no-polis">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Cabang</div>
                        <div class="detail-value" id="d-cabang">-</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Kematian</div>
                        <div class="detail-value" id="d-tanggal-kematian">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Lapor</div>
                        <div class="detail-value" id="d-tanggal-lapor">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Deskripsi</div>
                        <div class="detail-value" id="d-deskripsi">-</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="detail-item">
                        <div class="detail-label">Nilai Klaim</div>
                        <div class="detail-value big" id="d-nilai-klaim">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Dokumen Terunggah</div>
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
            <h5>Log Status</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped log-table" style="width:100%">
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

@endsection

@push('pageScripts')
    <script src="{{ asset('assets/js/client/klaim-detail.js') }}"></script>
@endpush
