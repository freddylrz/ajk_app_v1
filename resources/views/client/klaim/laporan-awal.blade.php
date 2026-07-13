@extends('layouts.app')

@section('pageTitle', 'Form Pengajuan Klaim')

@section('content')

<div class="pct-body">
<form id="form-laporan-awal">
    <div class="card">
        <div class="card-header">
            <i class="ti ti-file-alert"></i>
            <h3>Laporan Awal Klaim</h3>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kolom kiri --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Tanggal Lapor Klaim <span class="text-danger">*</span></label>
                        <div><strong class="fs-5" id="tanggal_lapor">-</strong></div>
                    </div>
                    <div class="form-group">
                        <label>Nama Peserta Asuransi <span class="text-danger">*</span></label>
                        <select class="form-select" id="peserta" name="peserta" required>
                            <option value="">-- Pilih Nama Peserta Asuransi --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Estimasi Besarnya Klaim <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control text-end" id="estimasi_klaim"
                                   name="estimasi_klaim" placeholder="Estimasi besarnya klaim" required>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Tanggal Kematian <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="tanggal_kematian"
                                   name="tanggal_kematian" placeholder="dd-mm-yyyy" required>
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="keterangan" name="keterangan"
                                  placeholder="Contoh: Sakit, Meninggal Dunia" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Unggah Laporan Awal Klaim <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file_laporan" accept="application/pdf" required>
                        <small class="form-text text-muted d-block">Dokumen dalam bentuk PDF.</small>
                        <a href="#!" class="fw-bold d-inline-block mt-2">
                            <i class="ti ti-download"></i> Unduh Draft Surat Pelaporan Awal Klaim
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-end text-muted small mb-3">KETERANGAN : (<span class="text-danger">*</span>) WAJIB DIISI</div>

            <div class="d-flex justify-content-end gap-3 flex-wrap">
                <button type="submit" class="btn btn-success">
                    <i class="ti ti-send"></i> Kirim Laporan
                </button>
                <a href="{{ route('client.klaim.data') }}" class="btn btn-danger">
                    <i class="ti ti-x"></i> Batal
                </a>
            </div>
        </div>
    </div>
</form>
</div>

@endsection

@push('levelPluginsJs')
    @vite(['resources/js/client/klaim-laporan-awal.js'])
@endpush
