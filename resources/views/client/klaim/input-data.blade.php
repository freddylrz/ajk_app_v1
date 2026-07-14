@extends('layouts.app')

@section('content')

<div class="pct-body">
<form id="form-input-data">
    <div class="card">
    <div class="card-header">
        <h3>Input Data Klaim</h3>
    </div>

    <div class="card-body">
        <div class="row g-4">

            {{-- Kolom Kiri --}}
            <div class="col-lg-6">

                <div class="mb-4">
                    <label class="form-label">
                        Tanggal Lapor Klaim <span class="text-danger">*</span>
                    </label>
                    <div>
                        <strong class="fs-5" id="tanggal_lapor">-</strong>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Nama Peserta Asuransi <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="peserta" name="peserta" required>
                        <option value="">-- Pilih Nama Peserta Asuransi --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Estimasi Besarnya Klaim <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text"
                               class="form-control text-end"
                               id="estimasi_klaim"
                               name="estimasi_klaim"
                               placeholder="Estimasi besarnya klaim"
                               required>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan --}}
            <div class="col-lg-6">

                <div class="mb-3">
                    <label class="form-label">
                        Tanggal Kematian <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text"
                               class="form-control datepicker"
                               id="tanggal_kematian"
                               name="tanggal_kematian"
                               placeholder="dd-mm-yyyy"
                               required>
                        <span class="input-group-text">
                            <i class="ti ti-calendar"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Keterangan <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control"
                              id="keterangan"
                              name="keterangan"
                              rows="5"
                              placeholder="Contoh: Sakit, Meninggal Dunia"
                              required></textarea>
                </div>

            </div>

        </div>

        <div class="text-end text-muted small">
            KETERANGAN : (<span class="text-danger">*</span>) WAJIB DIISI
        </div>
    </div>
</div>

    <div class="card">
        <div class="card-header">
            <h3>Dokumen Klaim</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive dt-responsive">
                <table class="table table-striped table-bordered nowrap" id="table-dokumen-klaim" style="width:100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Dokumen</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th>Tanggal Upload</th>
                            <th>Unggah</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <p class="mt-3 text-muted small">
                Catatan: dokumen yang sudah tersedia ditandai dengan tanda centang. Unggah dokumen yang belum ada sebelum submit ke SPV.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end gap-3 flex-wrap">
                <button type="submit" class="btn btn-success">
                    <i class="ti ti-upload"></i> Unggah & Submit to SPV
                </button>
            </div>
        </div>
    </div>
</form>
</div>

@endsection

@push('levelPluginsJs')
    @vite(['resources/js/client/klaim-input-data.js'])
@endpush
