@extends('layouts.app')

@section('pageTitle', 'Form Deklarasi')

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
                {{-- Kolom 1 --}}
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label>Nama Debitur <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_debitur" name="nama_debitur"
                               placeholder="Nama lengkap sesuai KTP" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Tanggal Lahir <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="tanggal_lahir"
                                       name="tanggal_lahir" placeholder="dd-mm-yyyy" required>
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Umur</label>
                            <input type="text" class="form-control" id="umur" placeholder="Umur" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>No. KTP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_ktp" name="no_ktp"
                                   placeholder="16 digit No. KTP" maxlength="16" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>No. HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp"
                                   placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Email <small class="text-muted">(opsional)</small></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="nama@email.com">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Alamat <small class="text-muted">(Sesuai dengan KTP)</small> <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_ktp" name="alamat_ktp" placeholder="Alamat sesuai KTP" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Alamat Tempat Tinggal <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" placeholder="Alamat domisili saat ini" required></textarea>
                    </div>
                </div>

                {{-- Kolom 2 --}}
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label>Kategori Debitur <span class="text-danger">*</span></label>
                        <select class="form-select" id="kategori_debitur" name="kategori_debitur" required>
                            <option value="">Pilih Kategori Debitur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Pangkat/Jabatan/Golongan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pangkat_jabatan" name="pangkat_jabatan"
                               placeholder="Contoh: Penata Muda / III-a" required>
                    </div>
                    <div class="mb-3">
                        <label>No. Rekening Pinjaman <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" id="cek_no_rek"
                                       title="Centang jika Anda sudah memiliki nomor rekening">
                            </div>
                            <input type="text" class="form-control" id="no_rek" name="no_rek" value="0" readonly>
                        </div>
                    </div>
                    <small class="form-text text-muted d-block">
                        1. No. Rekening Pinjaman harus sesuai dan bukan rekening tabungan.<br>
                        2. No. Rekening Pinjaman dan No. Perjanjian Kredit wajib dilengkapi.<br>
                        3. Apabila dalam 14 hari data belum lengkap, maka sistem akan otomatis terkunci.
                    </small>
                </div>

                {{-- Kolom 3 --}}
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label>Nama Instansi/Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_instansi" name="nama_instansi"
                               placeholder="Nama instansi/perusahaan tempat bekerja" required>
                    </div>
                    <div class="mb-3">
                        <label>No. PK <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" id="cek_no_pk"
                                       title="Centang jika Anda sudah memiliki nomor PK">
                            </div>
                            <input type="text" class="form-control" id="no_pk" name="no_pk" value="0" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end text-muted small">KETERANGAN : (<span class="text-danger">*</span>) WAJIB DIISI</div>
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
                <div class="col-md-4 mb-3">
                    <label>Tenor <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="tenor" name="tenor" placeholder="Tenor" min="1" required>
                        <span class="input-group-text">Bulan</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Periode Awal <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" id="periode_awal"
                               name="periode_awal" placeholder="dd-mm-yyyy" required>
                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                    </div>
                    <small class="form-text text-muted">Periode harus sesuai jangka waktu kredit pada Perjanjian Kredit (PK).</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Plafond Kredit <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control text-end" id="plafond_kredit"
                               name="plafond_kredit" placeholder="Plafond Kredit" required>
                    </div>
                </div>
            </div>
            <div class="text-end mb-3">
                <button type="button" class="btn btn-info" id="btn-hitung">
                    <i class="ti ti-calculator"></i> Hitung
                </button>
            </div>

            <hr>

            <h5 class="mb-3">Hasil :</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">Periode</small>
                    <strong id="output_periode">-</strong>
                </div>
                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">Rate</small>
                    <strong id="output_rate">-</strong>
                </div>
                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">Premi</small>
                    <strong id="output_premi">-</strong>
                </div>
            </div>
            <small class="form-text text-muted">Hanya untuk kredit konsumtif (Decreasing Term).</small>
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
                <div class="col-md-6 mb-4">
                    <label>1. Foto Asli KTP <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" style="max-width:320px;"
                        id="file_ktp" name="file_ktp[]" accept="image/*" multiple required>
                    <small class="form-text text-muted d-block">Tekan CTRL untuk memilih beberapa file.</small>
                    <ul class="list-unstyled small mt-2 mb-0" id="preview_file_ktp">
                        <li class="text-muted fst-italic">Belum ada file dipilih</li>
                    </ul>
                </div>
                <div class="col-md-6 mb-4">
                    <label>2. Foto Debitur pada Saat Penandatanganan PK <small class="text-muted">(Posisi Berdiri Tampak Depan dan Tampak Samping)</small> <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" style="max-width:320px;"
                        id="file_pk" name="file_pk[]" accept="image/*" multiple required>
                    <small class="form-text text-muted d-block">Tekan CTRL untuk memilih beberapa file.</small>
                    <ul class="list-unstyled small mt-2 mb-0" id="preview_file_pk">
                        <li class="text-muted fst-italic">Belum ada file dipilih</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ PERHATIAN + AKSI ══ --}}
    <div class="card">
        <div class="card-body">
            <div class="alert alert-warning mb-4">
                <strong><i class="ti ti-info-circle"></i> Perhatian :</strong>
                <p class="mb-1">Mohon dalam mengisi data debitur dan Keterangan Kesehatan harus sesuai dengan data yang sebenarnya,
                   agar tidak terjadi kendala pada saat proses pengajuan klaim.</p>
                <p class="mb-0">Apabila dalam 14 hari belum melengkapi data debitur, maka secara otomatis sistem
                   akan terkunci — silakan hubungi PIC TuguBro.</p>
            </div>
            <div class="d-flex justify-content-end gap-3 flex-wrap">
                <a href="{{ route('client.penutupan.list') }}" class="btn btn-danger">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
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
