@extends('layouts.app')

@section('content')

<div class="pct-body">
{{-- id dikirim dari controller, dibaca oleh penutupan-detail.js --}}
<div id="detail-container" data-id="{{ $id }}">

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-12 text-center mb-2">
                    <h3 class="mb-0">
                        <span id="head-no-polis">-</span>
                    </h3>
                </div>

                <div class="col-6">
                    <h5 class="mb-0">
                        No. Polis : <span id="head-no-polis-2">-</span>
                    </h5>
                </div>

                <div class="col-6 text-end">
                    <span id="head-status"></span>
                </div>
            </div>
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
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Alamat (Sesuai KTP)</small>
                            <strong id="d-alamat-ktp">-</strong>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Alamat Tempat Tinggal</small>
                            <strong id="d-alamat-domisili">-</strong>
                        </div>
                    </div>
                </div>

                {{-- Kolom 2: instansi & pinjaman --}}
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Nama Instansi/Perusahaan</small>
                            <strong id="d-instansi">-</strong>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Pangkat/Jabatan/Golongan</small>
                            <strong id="d-pangkat">-</strong>
                        </div>
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
                            <small class="text-muted d-block">Periode Awal</small>
                            <strong id="d-periode-awal">-</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Periode Akhir</small>
                        <strong id="d-periode-akhir">-</strong>
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
                        <small class="text-muted d-block">Dokumen Terunggah</small>
                        <ul class="list-unstyled mt-2 mb-0" id="d-files"></ul>
                    </div>
                </div>
            </div>

            {{-- Aksi khusus SPV: validasi (tampil hanya jika role SPV & status Menunggu Validasi SPV) --}}
            <div class="alert alert-light border d-none" id="area-validasi-spv">
                <h5 class="mb-3"><i class="ti ti-user-check"></i> Validasi SPV</h5>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea class="form-control" id="catatan_validasi" rows="2"
                              placeholder="Wajib diisi jika data dikembalikan ke Operator"></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2 flex-wrap mt-2">
                    <button type="button" class="btn btn-warning" id="btn-kembalikan">
                        <i class="ti ti-arrow-back-up"></i> Kembalikan ke Operator
                    </button>
                    <button type="button" class="btn btn-success" id="btn-setujui">
                        <i class="ti ti-check"></i> Setujui &amp; Teruskan ke TuguBro
                    </button>
                </div>
            </div>

            <div class="text-end mt-3 d-flex justify-content-end gap-2 flex-wrap">
                <a href="#" class="btn btn-success d-none" id="btn-edit">
                    <i class="ti ti-edit"></i> Edit Data
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
    @vite(['resources/js/client/penutupan-detail.js'])
@endpush
