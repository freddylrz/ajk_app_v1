@extends('layouts.app')

@section('pageTitle', 'Detail Penutupan')

@section('content')

<div class="pct-body">
{{-- id dikirim dari controller, dibaca oleh penutupan-detail.js --}}
<div id="detail-container" data-id="{{ $id }}">

    <div class="card">
        <div class="card-header">
            <i class="ti ti-file-certificate"></i>
            <h3>No. Polis : <span id="head-no-polis">-</span></h3>
            <span class="ms-auto" id="head-status"></span>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kolom 1: data diri --}}
                <div class="col-lg-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Kategori Debitur</small>
                        <strong id="d-kategori-debitur">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Debitur</small>
                        <strong class="fs-5" id="d-debitur">-</strong>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Tanggal Lahir</small>
                            <strong id="d-tanggal-lahir">-</strong>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Umur</small>
                            <strong id="d-umur">-</strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">No. KTP</small>
                            <strong id="d-no-ktp">-</strong>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Jenis Kelamin</small>
                            <strong id="d-jenis-kelamin">-</strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">No. HP</small>
                            <strong id="d-no-hp">-</strong>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Email</small>
                            <strong id="d-email">-</strong>
                        </div>
                    </div>
                </div>

                {{-- Kolom 2: instansi & pinjaman --}}
                <div class="col-lg-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Nama Instansi/Perusahaan</small>
                        <strong id="d-instansi">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Pangkat/Jabatan/Golongan</small>
                        <strong id="d-pangkat">-</strong>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">No. Rek</small>
                            <strong id="d-no-rek">-</strong>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">No. PK</small>
                            <strong id="d-no-pk">-</strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Tenor</small>
                            <strong id="d-tenor">-</strong>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Input Date</small>
                            <strong id="d-input-date">-</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Periode</small>
                        <strong id="d-periode">-</strong>
                    </div>
                </div>

                {{-- Kolom 3: nilai, alamat & dokumen --}}
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Plafond Kredit</small>
                            <strong class="fs-5" id="d-plafond">-</strong>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Rate Premi</small>
                            <strong id="d-rate">-</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Nilai Premi</small>
                        <strong class="fs-5" id="d-premi">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Alamat (Sesuai KTP)</small>
                        <strong id="d-alamat-ktp">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Alamat Tempat Tinggal</small>
                        <strong id="d-alamat-domisili">-</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Dokumen Terunggah</small>
                        <ul class="list-unstyled mt-2 mb-0" id="d-files"></ul>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('client.penutupan.list') }}" class="btn btn-danger">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- ══ Keterangan Kesehatan ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ti ti-heart-rate-monitor"></i>
            <h3>Keterangan Kesehatan</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive dt-responsive">
                <table class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:60px;">No.</th>
                            <th>Pertanyaan</th>
                            <th style="width:110px;">Jawaban</th>
                            <th style="width:260px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="d-kesehatan"></tbody>
                </table>
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
    @vite(['resources/js/client/penutupan-detail.js'])
@endpush
