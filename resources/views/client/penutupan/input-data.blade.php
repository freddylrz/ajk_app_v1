@extends('layouts.app')

@section('pageTitle', 'Form Deklarasi')

@push('pageStyles')
<style>
    .client-form-actions-spacer {
        height: 110px;
    }

    .client-form-actions {
        --client-form-actions-gap: 16px;
        --client-form-actions-bottom: var(--client-form-actions-gap);
        position: fixed;
        right: 40px;
        bottom: max(var(--client-form-actions-bottom), env(safe-area-inset-bottom));
        left: 320px;
        z-index: 1025;
        pointer-events: none;
        transition: left 0.2s ease;
    }

    .client-form-actions .card {
        margin-bottom: 0;
        border: 1px solid rgba(0, 0, 0, 0.08);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 12px 32px rgba(31, 45, 61, 0.18);
        backdrop-filter: blur(10px);
        pointer-events: auto;
    }

    .pc-sidebar.pc-sidebar-hide ~ .pc-container .client-form-actions {
        left: 40px;
    }

    @media (max-width: 1024.98px) {
        .client-form-actions {
            right: 20px;
            left: 20px;
        }
    }

    @media (max-width: 575.98px) {
        .client-form-actions-spacer {
            height: 155px;
        }

        .client-form-actions {
            --client-form-actions-gap: 12px;
            right: 12px;
            left: 12px;
        }

        .client-form-actions__content,
        .client-form-actions__buttons {
            width: 100%;
        }

        .client-form-actions__buttons .btn {
            flex: 1 1 0;
        }
    }
</style>
@endpush

@section('content')

<div class="pct-body">
<form id="form-deklarasi">

    {{-- ══ DATA DEBITUR ══ --}}
    <div class="card">
        <div class="card-header">
            <h3>Data Debitur</h3>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kolom 1 --}}
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Nama Debitur <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_debitur" name="nama_debitur"
                               placeholder="Nama lengkap sesuai KTP" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="tanggal_lahir"
                                           name="tanggal_lahir" placeholder="dd-mm-yyyy" required>
                                    <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Umur</label>
                                <input type="text" class="form-control" id="umur" placeholder="Umur" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>No. KTP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_ktp" name="no_ktp"
                                       placeholder="16 digit No. KTP" maxlength="16" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>No. HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp"
                                       placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Email </label>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="nama@email.com">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat <small class="text-muted">(Sesuai dengan KTP)</small> <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_ktp" name="alamat_ktp" placeholder="Alamat sesuai KTP" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Alamat Tempat Tinggal <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" placeholder="Alamat domisili saat ini" required></textarea>
                    </div>
                </div>

                {{-- Kolom 2 --}}
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori Debitur <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori_debitur" name="kategori_debitur" required>
                                    <option value="">Pilih Kategori Debitur</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Instansi/Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_instansi" name="nama_instansi"
                                    placeholder="Nama instansi/perusahaan tempat bekerja" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pangkat/Jabatan/Golongan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pangkat_jabatan" name="pangkat_jabatan"
                               placeholder="Contoh: Penata Muda / III-a" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Rekening Pinjaman <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" id="cek_no_rek"
                                            title="Centang jika Anda sudah memiliki nomor rekening">
                                    </div>
                                    <input type="text" class="form-control" id="no_rek" name="no_rek" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
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
                    <small class="form-text text-muted d-block">
                        1. No. Rekening Pinjaman harus sesuai dan bukan rekening tabungan.<br>
                        2. No. Rekening Pinjaman dan No. Perjanjian Kredit wajib dilengkapi.<br>
                        3. Apabila dalam 14 hari data belum lengkap, maka sistem akan otomatis terkunci.
                    </small>
                </div>
            </div>
            <div class="text-end text-muted small">KETERANGAN : (<span class="text-danger">*</span>) WAJIB DIISI</div>
        </div>
    </div>

    {{-- ══ PERHITUNGAN PREMI ══ --}}
    <div class="card">
        <div class="card-header">
            <h3>Perhitungan Premi</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tenor <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="tenor" name="tenor" placeholder="Tenor" min="1" required>
                            <span class="input-group-text">Bulan</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Periode Awal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="periode_awal"
                                   name="periode_awal" placeholder="dd-mm-yyyy" required>
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        </div>
                        <small class="form-text text-muted">Periode harus sesuai jangka waktu kredit pada Perjanjian Kredit (PK).</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Plafond Kredit <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control text-end" id="plafond_kredit"
                                   name="plafond_kredit" placeholder="Plafond Kredit" required>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-0">

            <h5 class="mb-3">Hasil:</h5>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted d-block">Periode</small>
                    <strong id="output_periode">-</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Rate</small>
                    <strong id="output_rate">-</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Premi</small>
                    <strong id="output_premi">-</strong>
                </div>
            </div>
            {{-- Nilai rate, premium & end_date hasil hitung disimpan di sini untuk dikirim saat submit --}}
            <input type="hidden" id="rate" name="rate">
            <input type="hidden" id="premium" name="premium">
            <input type="hidden" id="end_date_computed" name="end_date_computed">
        </div>
    </div>

    {{-- ══ UNGGAH DOKUMEN ══ --}}
    <div class="card">
        <div class="card-header">
            <h3>Unggah Dokumen</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>1. Foto Asli KTP <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm" style="max-width:320px;"
                            id="file_ktp" name="file_ktp" accept="image/*" required>
                        <small class="form-text text-muted d-block">File foto/scan KTP.</small>
                        <ul class="list-unstyled small mt-2 mb-0" id="preview_file_ktp">
                            <li class="text-muted fst-italic">Belum ada file dipilih</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
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
    </div>

    {{-- ══ PERHATIAN ══ --}}
    <div class="card">
        <div class="card-body">
            <div class="alert alert-warning mb-0">
                <strong><i class="ti ti-info-circle"></i> Perhatian :</strong>
                <p class="mb-1">Mohon dalam mengisi data debitur dan Keterangan Kesehatan harus sesuai dengan data yang sebenarnya,
                   agar tidak terjadi kendala pada saat proses pengajuan klaim.</p>
                <p class="mb-0">Apabila dalam 14 hari belum melengkapi data debitur, maka secara otomatis sistem
                   akan terkunci — silakan hubungi PIC TuguBro.</p>
            </div>
        </div>
    </div>

    <div class="client-form-actions-spacer" aria-hidden="true"></div>

    {{-- ══ FLOATING FORM ACTIONS ══ --}}
    <div class="client-form-actions">
        <div class="card">
            <div class="card-body py-3 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div class="client-form-actions__content" role="status" aria-live="polite">
                    <span class="badge bg-warning text-dark" id="premium-calculation-status">
                        Premi perlu dihitung
                    </span>
                    <small class="text-muted d-block mt-1" id="premium-calculation-hint">
                        Hitung kembali premi setelah melengkapi atau mengubah form.
                    </small>
                </div>
                <div class="client-form-actions__buttons d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-info" id="btn-hitung">
                        <i class="ti ti-calculator"></i> Hitung Premi
                    </button>
                    <button type="submit" class="btn btn-success" id="btn-simpan" disabled>
                        <i class="ti ti-device-floppy"></i> Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>
</div>

@endsection

@push('levelPluginsJs')
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    @vite(['resources/js/client/penutupan/input-data.js'])
@endpush
