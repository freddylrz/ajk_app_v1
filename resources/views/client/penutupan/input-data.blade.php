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
                            <label>Kategori Debitur <span class="required-star">*</span></label>
                            <select class="form-select" id="kategori_debitur" name="kategori_debitur" required>
                                <option value="">Pilih Kategori Debitur</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nama Instansi/Perusahaan <span class="required-star">*</span></label>
                            <input type="text" class="form-control" id="nama_instansi" name="nama_instansi"
                                   placeholder="Nama instansi/perusahaan tempat bekerja" required>
                        </div>
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
                    <div class="mb-3">
                        <label>Pangkat/Jabatan/Golongan <span class="required-star">*</span></label>
                        <input type="text" class="form-control" id="pangkat_jabatan" name="pangkat_jabatan"
                               placeholder="Contoh: Penata Muda / III-a" required>
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>No. HP <span class="required-star">*</span></label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp"
                                   placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email <small>(opsional)</small></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="nama@email.com">
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="col-lg-6">
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
                    <div class="form-hint mb-3" style="margin-top:-8px;">
                        1. No. Rekening Pinjaman harus sesuai dan bukan rekening tabungan.<br>
                        2. No. Rekening Pinjaman dan No. Perjanjian Kredit wajib dilengkapi.<br>
                        3. Apabila dalam 14 hari data belum lengkap, maka sistem akan otomatis terkunci.
                    </div>
                    <div class="mb-3">
                        <label>Alamat <small>(Sesuai dengan KTP)</small> <span class="required-star">*</span></label>
                        <textarea class="form-control" id="alamat_ktp" name="alamat_ktp" placeholder="Alamat sesuai KTP" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Alamat Tempat Tinggal <span class="required-star">*</span></label>
                        <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" placeholder="Alamat domisili saat ini" required></textarea>
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
                <div class="col-lg-5">
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
                        <div class="form-hint">Periode harus sesuai jangka waktu kredit pada Perjanjian Kredit (PK).</div>
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

                <div class="col-lg-7">
                    <h5 class="mb-3" style="font-weight:800;color:#008743;border-bottom:3px solid #e6f7ee;display:inline-block;padding-bottom:4px;">
                        Hasil :
                    </h5>
                    <div class="row">
                        <div class="col-md-4 detail-item">
                            <div class="detail-label">Periode</div>
                            <div class="detail-value" id="output_periode">-</div>
                        </div>
                        <div class="col-md-4 detail-item">
                            <div class="detail-label">Rate</div>
                            <div class="detail-value" id="output_rate">-</div>
                        </div>
                        <div class="col-md-4 detail-item">
                            <div class="detail-label">Premi</div>
                            <div class="detail-value big" id="output_premi">-</div>
                        </div>
                    </div>
                    <div class="form-hint">Hanya untuk kredit konsumtif (Decreasing Term).</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ KETERANGAN KESEHATAN ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ti ti-heart-rate-monitor"></i>
            <h5>Keterangan Kesehatan</h5>
        </div>
        <div class="card-body">
            <div class="callout-attention mb-4" style="border-left-color:#38bdf8;background:#eff9ff;">
                <h5 style="color:#075985;"><i class="ti ti-info-circle"></i> Perhatian!</h5>
                <p style="color:#0c4a6e;">Pernyataan di bawah ini harus sesuai dengan Surat Pernyataan Kesehatan (SPK)
                   yang telah diisi dengan lengkap, benar dan telah ditandatangani oleh pihak debitur dan pihak bank.</p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-kesehatan" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:60px;">No.</th>
                            <th>Pertanyaan</th>
                            <th style="width:140px;">Jawaban</th>
                            <th style="width:260px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-kesehatan"></tbody>
                </table>
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
            <div class="upload-item">
                <div class="upload-title"><i class="ti ti-id"></i> 1. Foto Asli KTP <span class="required-star">*</span></div>
                <input type="file" class="form-control" name="file_ktp[]" accept="image/*" multiple required>
                <div class="form-hint">Tekan CTRL untuk memilih beberapa file.</div>
            </div>
            <div class="upload-item">
                <div class="upload-title"><i class="ti ti-signature"></i> 2. Foto Debitur pada Saat Penandatanganan PK <small>(Posisi Berdiri Tampak Depan dan Tampak Samping)</small> <span class="required-star">*</span></div>
                <input type="file" class="form-control" name="file_pk[]" accept="image/*" multiple required>
                <div class="form-hint">Tekan CTRL untuk memilih beberapa file.</div>
            </div>
            <div class="upload-item">
                <div class="upload-title"><i class="ti ti-file-text"></i> 3. Surat Pernyataan Kesehatan (SPK) yang Sudah Diisi Lengkap dan Ditandatangani <span class="required-star">*</span></div>
                <input type="file" class="form-control" name="file_spk" accept="application/pdf" required>
                <div class="form-hint">Dokumen dalam bentuk PDF.</div>
            </div>
        </div>
    </div>

    {{-- ══ PERHATIAN + AKSI ══ --}}
    <div class="card">
        <div class="card-body">
            <div class="callout-attention mb-4">
                <h5><i class="ti ti-info-circle"></i> Perhatian :</h5>
                <p>Mohon dalam mengisi data debitur dan SPK harus sesuai dengan data yang sebenarnya,
                   agar tidak terjadi kendala pada saat proses pengajuan klaim.</p>
                <p>Apabila dalam 14 hari belum melengkapi data debitur, maka secara otomatis sistem
                   akan terkunci — silakan hubungi PIC TuguBro.</p>
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
    @vite(['resources/js/client/penutupan-input.js'])
@endpush
