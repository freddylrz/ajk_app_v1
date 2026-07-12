@extends('layouts.app')

@section('pageTitle', 'Form Pengajuan Klaim')
@section('pageIcon', 'ti ti-alert-circle')

@section('content')

<form id="form-laporan-awal">
    <div class="card">
        <div class="card-header">
            <i class="ti ti-file-alert"></i>
            <h5>Laporan Awal Klaim</h5>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kolom kiri --}}
                <div class="col-lg-6">
                    <div class="mb-4">
                        <label>Tanggal Lapor Klaim <span class="required-star">*</span></label>
                        <div class="detail-value big" id="tanggal_lapor">-</div>
                    </div>
                    <div class="mb-3">
                        <label>Nama Peserta Asuransi <span class="required-star">*</span></label>
                        <select class="form-select" id="peserta" name="peserta" required>
                            <option value="">-- Pilih Nama Peserta Asuransi --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Estimasi Besarnya Klaim <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control text-end" id="estimasi_klaim"
                                   name="estimasi_klaim" placeholder="Estimasi besarnya klaim" required>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label>Tanggal Kematian <span class="required-star">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="tanggal_kematian"
                                   name="tanggal_kematian" placeholder="dd-mm-yyyy" required>
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan <span class="required-star">*</span></label>
                        <textarea class="form-control" id="keterangan" name="keterangan"
                                  placeholder="Contoh: Sakit, Meninggal Dunia" required></textarea>
                    </div>
                    <div class="upload-item">
                        <div class="upload-title"><i class="ti ti-file-upload"></i> Unggah Laporan Awal Klaim <span class="required-star">*</span></div>
                        <input type="file" class="form-control" name="file_laporan" accept="application/pdf" required>
                        <div class="form-hint">Dokumen dalam bentuk PDF.</div>
                        <a href="#!" class="fw-bold d-inline-block mt-2" style="font-size:15px;">
                            <i class="ti ti-download"></i> Unduh Draft Surat Pelaporan Awal Klaim
                        </a>
                    </div>
                </div>
            </div>

            <div class="mandatory-note mb-3">KETERANGAN : (<span class="required-star">*</span>) WAJIB DIISI</div>

            <div class="d-flex justify-content-end gap-3 flex-wrap">
                <a href="{{ route('client.klaim.data') }}" class="btn btn-danger btn-lg">
                    <i class="ti ti-x"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="ti ti-send"></i> Kirim Laporan
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('pageScripts')
    <script src="{{ asset('assets/js/client/klaim-laporan-awal.js') }}"></script>
@endpush
