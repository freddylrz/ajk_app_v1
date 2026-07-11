@extends('layouts.app')

@section('pageTitle', 'Form Deklarasi')
@section('pageIcon', 'ti ti-file-plus')

@section('content')

<form id="form-deklarasi">

    {{-- ══ DATA DEBITUR ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ti ti-user"></i>
            <h5>Data Debitur</h5>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kolom kiri --}}
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label>Nama Debitur <span class="required-star">*</span></label>
                        <input type="text" class="form-control" id="nama_debitur" name="nama_debitur"
                               placeholder="Nama lengkap sesuai KTP" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Lahir <span class="required-star">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="tanggal_lahir"
                                       name="tanggal_lahir" placeholder="dd-mm-yyyy" required>
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Umur</label>
                            <input type="text" class="form-control" id="umur" placeholder="Umur" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>No. KTP <span class="required-star">*</span></label>
                            <input type="text" class="form-control" id="no_ktp" name="no_ktp"
                                   placeholder="16 digit No. KTP" maxlength="16" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis Kelamin <span class="required-star">*</span></label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label>Alamat <small>(Sesuai dengan KTP)</small> <span class="required-star">*</span></label>
                        <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat lengkap" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Kategori Debitur <span class="required-star">*</span></label>
                            <select class="form-select" id="kategori_debitur" name="kategori_debitur" required>
                                <option value="">Pilih Kategori Debitur</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Institusi <span class="required-star">*</span></label>
                            <select class="form-select" id="institusi" name="institusi" required>
                                <option value="">Pilih Institusi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>No. Rekening Pinjaman <span class="required-star">*</span></label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="cek_no_rek" checked
                                           title="Centang jika belum ada nomor rekening">
                                </div>
                                <input type="text" class="form-control" id="no_rek" name="no_rek" value="0" readonly>
                            </div>
                            <div class="form-hint">Nomor rekening pinjaman harus sesuai dan bukan nomor rekening tabungan.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>No. PK <span class="required-star">*</span></label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="cek_no_pk" checked
                                           title="Centang jika belum ada nomor PK">
                                </div>
                                <input type="text" class="form-control" id="no_pk" name="no_pk" value="0" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mandatory-note">KETERANGAN : (<span class="required-star">*</span>) WAJIB DIISI</div>
        </div>
    </div>

    {{-- ══ PERHITUNGAN PREMI ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ti ti-calculator"></i>
            <h5>Perhitungan Premi</h5>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Input data --}}
                <div class="col-lg-6">
                    <h5 class="mb-3" style="font-weight:800;color:#008743;border-bottom:3px solid #e6f7ee;display:inline-block;padding-bottom:4px;">
                        Input Data :
                    </h5>
                    <div class="mb-3">
                        <label>Tenor <span class="required-star">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="tenor" name="tenor" placeholder="Tenor" min="1" required>
                            <span class="input-group-text">Bulan</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Periode Awal <span class="required-star">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="periode_awal"
                                   name="periode_awal" placeholder="dd-mm-yyyy" required>
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        </div>
                        <div class="form-hint">Periode harus sesuai tanggal PK.</div>
                    </div>
                    <div class="mb-3">
                        <label>Plafond Kredit <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control text-end" id="plafond_kredit"
                                   name="plafond_kredit" placeholder="Plafond Kredit" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-info" id="btn-hitung">
                            <i class="ti ti-calculator"></i> Hitung
                        </button>
                    </div>
                </div>

                {{-- Output data --}}
                <div class="col-lg-6">
                    <h5 class="mb-3" style="font-weight:800;color:#008743;border-bottom:3px solid #e6f7ee;display:inline-block;padding-bottom:4px;">
                        Output Data :
                    </h5>
                    <div class="detail-item">
                        <div class="detail-label">Periode</div>
                        <div class="detail-value" id="output_periode">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Rate</div>
                        <div class="detail-value" id="output_rate">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Premi</div>
                        <div class="detail-value big" id="output_premi">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ UNGGAH DOKUMEN ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ti ti-cloud-upload"></i>
            <h5>Unggah Dokumen</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="upload-item">
                        <div class="upload-title"><i class="ti ti-id"></i> 1. Foto atau Fotokopi KTP</div>
                        <input type="file" class="form-control" name="file_ktp" accept="image/*">
                    </div>
                    <div class="upload-item">
                        <div class="upload-title"><i class="ti ti-signature"></i> 2. Foto Saat Tanda Tangan PK</div>
                        <input type="file" class="form-control" name="file_ttd_pk" accept="image/*">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="upload-item">
                        <div class="upload-title"><i class="ti ti-user-scan"></i> 3. Foto Posisi Berdiri Tampak Depan</div>
                        <input type="file" class="form-control" name="file_berdiri_depan" accept="image/*">
                    </div>
                    <div class="upload-item">
                        <div class="upload-title"><i class="ti ti-user-scan"></i> 4. Foto Posisi Berdiri Tampak Samping</div>
                        <input type="file" class="form-control" name="file_berdiri_samping" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ PERHATIAN + AKSI ══ --}}
    <div class="card">
        <div class="card-body">
            <div class="callout-attention mb-4">
                <h5><i class="ti ti-info-circle"></i> Perhatian :</h5>
                <p>Mohon diperhatikan dalam pengisian data debitur harus sesuai dengan dokumen asli,
                   agar tidak terjadi dispute pada saat pengajuan klaim/penolakan.</p>
                <p>Perusahaan Pialang Asuransi dan Perusahaan Asuransi tidak dibenarkan memberikan dan
                   tidak menggunakan data dan/atau informasi pribadi Nasabah BNI untuk tujuan apapun
                   selain dari yang berkaitan dengan penutupan Asuransi.</p>
                <p>Dengan disetujuinya penutupan polis Asuransi ini oleh Nasabah, secara otomatis juga
                   menyetujui ketentuan syarat dan kondisi yang berlaku pada klausula Asuransi tersebut.</p>
            </div>
            <div class="d-flex justify-content-end gap-3 flex-wrap">
                <a href="{{ route('client.penutupan.list') }}" class="btn btn-danger btn-lg">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="ti ti-device-floppy"></i> Simpan Data
                </button>
            </div>
        </div>
    </div>

</form>

@endsection

@push('pageScripts')
    <script src="{{ asset('assets/js/client/penutupan-input.js') }}"></script>
@endpush
