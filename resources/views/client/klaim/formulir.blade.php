@extends('layouts.app')

@section('pageTitle', 'Daftar Formulir Klaim Reguler')

@section('content')

<div class="pct-body">
<div class="card">
    <div class="card-header">
        <i class="ti ti-table"></i>
        <h3>Klaim yang Perlu Dilengkapi Formulir</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive dt-responsive">
            <table class="table table-striped table-bordered nowrap" id="table-formulir" style="width:100%">
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
</div>

{{-- ══ Modal unggah formulir klaim ══ --}}
<div class="modal fade" id="modal-formulir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-unggah-formulir">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-file-upload"></i> Lengkapi Formulir Klaim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Klaim ID</label>
                        <div><strong class="fs-5" id="modal-klaim-id">-</strong></div>
                        <div id="modal-debitur">-</div>
                    </div>
                    <div class="form-group">
                        <label>1. Formulir Klaim (PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file_formulir" accept="application/pdf" required>
                        <a href="#!" class="fw-bold d-inline-block mt-2">
                            <i class="ti ti-download"></i> Unduh Template Formulir Klaim
                        </a>
                    </div>
                    <div class="form-group">
                        <label>2. Dokumen Pendukung <small class="text-muted">(Surat keterangan kematian, dll)</small></label>
                        <input type="file" class="form-control" name="file_pendukung" accept="application/pdf" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-send"></i> Kirim Formulir
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('levelPluginsJs')
    @vite(['resources/js/client/klaim-formulir.js'])
@endpush
