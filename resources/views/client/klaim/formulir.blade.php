@extends('layouts.app')

@section('pageTitle', 'Daftar Formulir Klaim Reguler')
@section('pageIcon', 'ti ti-forms')

@section('content')

<div class="card">
    <div class="card-header">
        <i class="ti ti-table"></i>
        <h5>Klaim yang Perlu Dilengkapi Formulir</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-formulir" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Klaim ID</th>
                        <th>Debitur</th>
                        <th>No. Polis</th>
                        <th>Cabang</th>
                        <th>Tanggal Kematian</th>
                        <th>Nilai Klaim</th>
                        <th>Tanggal Lapor</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══ Modal unggah formulir klaim ══ --}}
<div class="modal fade" id="modal-formulir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form-unggah-formulir">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-file-upload"></i> Lengkapi Formulir Klaim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label>Klaim ID</label>
                        <div><strong class="fs-5" id="modal-klaim-id">-</strong></div>
                        <div id="modal-debitur">-</div>
                    </div>
                    <div class="mb-3">
                        <label>1. Formulir Klaim (PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file_formulir" accept="application/pdf" required>
                        <a href="#!" class="fw-bold d-inline-block mt-2">
                            <i class="ti ti-download"></i> Unduh Template Formulir Klaim
                        </a>
                    </div>
                    <div class="mb-3">
                        <label>2. Dokumen Pendukung <small class="text-muted">(Surat keterangan kematian, dll)</small></label>
                        <input type="file" class="form-control" name="file_pendukung" accept="application/pdf" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-send"></i> Kirim Formulir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('pageScripts')
    @vite(['resources/js/client/klaim-formulir.js'])
@endpush
