@extends('layouts.app')

@section('pageTitle', 'Detail Penutupan')
@section('pageIcon', 'ti ti-file-search')

@section('content')

{{-- id dikirim dari controller, dibaca oleh penutupan-detail.js --}}
<div id="detail-container" data-id="{{ $id }}">

    <div class="card">
        <div class="card-header">
            <i class="ti ti-file-certificate"></i>
            <h5>No. Polis : <span id="head-no-polis">-</span></h5>
            <span class="ms-auto" id="head-status"></span>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kolom 1: data diri --}}
                <div class="col-lg-4">
                    <div class="detail-item">
                        <div class="detail-label">Kategori Debitur</div>
                        <div class="detail-value" id="d-kategori-debitur">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Debitur</div>
                        <div class="detail-value big" id="d-debitur">-</div>
                    </div>
                    <div class="row">
                        <div class="col-6 detail-item">
                            <div class="detail-label">Tanggal Lahir</div>
                            <div class="detail-value" id="d-tanggal-lahir">-</div>
                        </div>
                        <div class="col-6 detail-item">
                            <div class="detail-label">Umur</div>
                            <div class="detail-value" id="d-umur">-</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 detail-item">
                            <div class="detail-label">No. KTP</div>
                            <div class="detail-value" id="d-no-ktp">-</div>
                        </div>
                        <div class="col-6 detail-item">
                            <div class="detail-label">Jenis Kelamin</div>
                            <div class="detail-value" id="d-jenis-kelamin">-</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 detail-item">
                            <div class="detail-label">No. HP</div>
                            <div class="detail-value" id="d-no-hp">-</div>
                        </div>
                        <div class="col-6 detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value" id="d-email">-</div>
                        </div>
                    </div>
                </div>

                {{-- Kolom 2: instansi & pinjaman --}}
                <div class="col-lg-4">
                    <div class="detail-item">
                        <div class="detail-label">Nama Instansi/Perusahaan</div>
                        <div class="detail-value" id="d-instansi">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Pangkat/Jabatan/Golongan</div>
                        <div class="detail-value" id="d-pangkat">-</div>
                    </div>
                    <div class="row">
                        <div class="col-6 detail-item">
                            <div class="detail-label">No. Rek</div>
                            <div class="detail-value" id="d-no-rek">-</div>
                        </div>
                        <div class="col-6 detail-item">
                            <div class="detail-label">No. PK</div>
                            <div class="detail-value" id="d-no-pk">-</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 detail-item">
                            <div class="detail-label">Tenor</div>
                            <div class="detail-value" id="d-tenor">-</div>
                        </div>
                        <div class="col-6 detail-item">
                            <div class="detail-label">Input Date</div>
                            <div class="detail-value" id="d-input-date">-</div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Periode</div>
                        <div class="detail-value" id="d-periode">-</div>
                    </div>
                </div>

                {{-- Kolom 3: nilai, alamat & dokumen --}}
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-6 detail-item">
                            <div class="detail-label">Plafond Kredit</div>
                            <div class="detail-value big" id="d-plafond">-</div>
                        </div>
                        <div class="col-6 detail-item">
                            <div class="detail-label">Rate Premi</div>
                            <div class="detail-value" id="d-rate">-</div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nilai Premi</div>
                        <div class="detail-value big" id="d-premi">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Alamat (Sesuai KTP)</div>
                        <div class="detail-value" id="d-alamat-ktp">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Alamat Tempat Tinggal</div>
                        <div class="detail-value" id="d-alamat-domisili">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Dokumen Terunggah</div>
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
            <h5>Keterangan Kesehatan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" style="width:100%">
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
    @vite(['resources/js/client/penutupan-detail.js'])
@endpush
