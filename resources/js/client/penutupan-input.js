/**
 * ============================================================
 * PAGE: Penutupan — Input Data (Form Deklarasi Reguler Griya)
 * API  : GET  /api/v1/client/declaration/asset  → opsi Kategori Debitur & Jenis Kelamin
 *        POST /api/v1/client/declaration/insert → simpan deklarasi baru
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

$(function () {

    /* ── Muat opsi Kategori Debitur & Jenis Kelamin dari API ──
       Jika API gagal/belum siap, dropdown dibiarkan kosong
       (tidak diisi data bikinan). */
    async function loadAsset() {
        try {
            const res = await ClientHelper.apiFetch('/api/v1/client/declaration/asset');
            const json = await res.json();

            if (!res.ok) {
                ClientHelper.notify(json.message || 'Gagal memuat data referensi form.', 'warning');
                return;
            }

            (json.data?.debt_category || []).forEach(item => {
                $('#kategori_debitur').append(new Option(item.category_name, item.id));
            });

            (json.data?.gender || []).forEach(item => {
                $('#jenis_kelamin').append(new Option(item.name, item.id));
            });
        } catch (err) {
            console.error('Gagal memuat /declaration/asset:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server untuk memuat data referensi form.', 'danger');
        } finally {
            $('#kategori_debitur, #jenis_kelamin').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    }
    loadAsset();

    /* ── Datepicker (format dd-mm-yyyy) ── */
    document.querySelectorAll('.datepicker').forEach(el => {
        new Datepicker(el, {
            format: 'dd-mm-yyyy',
            autohide: true,
            language: 'en'
        });
    });

    /* ── Hitung umur otomatis dari tanggal lahir ── */
    $('#tanggal_lahir').on('changeDate change', function () {
        const val = $(this).val(); // dd-mm-yyyy
        if (!val) return;
        const [d, m, y] = val.split('-');
        const umur = ClientHelper.hitungUmur(`${y}-${m}-${d}`);
        $('#umur').val(umur ? umur + ' Tahun' : '');
    });

    /* ── Hitung premi: memanggil API premi-calculation ──
       Endpoint /api/v1/client/declaration/premi-calculation belum
       diimplementasikan di server. Pemanggilannya disiapkan di
       bawah ini (di-comment) supaya tinggal diaktifkan begitu
       endpoint tersedia — untuk sementara hasil dibiarkan kosong. */
    $('#btn-hitung').on('click', async function () {
        const tenor = parseInt($('#tenor').val(), 10);
        const periodeAwal = $('#periode_awal').val();
        const plafond = parseInt($('#plafond_kredit').val().replace(/[^\d]/g, ''), 10);

        if (!tenor || !periodeAwal || !plafond) {
            ClientHelper.notify('Mohon lengkapi Tenor, Periode Awal, dan Plafond Kredit terlebih dahulu.', 'warning');
            return;
        }

        // TODO: aktifkan begitu /api/v1/client/declaration/premi-calculation tersedia.
        // try {
        //     const res = await ClientHelper.apiFetch('/api/v1/client/declaration/premi-calculation', {
        //         method: 'POST',
        //         headers: { 'Content-Type': 'application/json' },
        //         body: JSON.stringify({
        //             tenor: tenor,
        //             start_date: toIsoDate(periodeAwal),
        //             plafond: plafond
        //         })
        //     });
        //     const json = await res.json();
        //
        //     if (!res.ok) {
        //         ClientHelper.notify(json.message || 'Gagal menghitung premi.', 'warning');
        //         return;
        //     }
        //
        //     $('#output_periode').text(json.data.periode);
        //     $('#output_rate').text(json.data.rate + ' %');
        //     $('#output_premi').text(ClientHelper.formatIDR(json.data.premium));
        //     $('#rate').val(json.data.rate);
        //     $('#premium').val(json.data.premium);
        // } catch (err) {
        //     console.error('Gagal memanggil /declaration/premi-calculation:', err);
        //     ClientHelper.notify('Tidak dapat terhubung ke server.', 'danger');
        // }

        ClientHelper.notify('Fitur hitung premi belum tersedia di server.', 'warning');
    });

    /* ── Checkbox No. Rek & No. PK: dicentang = sudah punya nomor (bisa diisi) ── */
    function toggleCheckField(checkbox, input) {
        $(checkbox).on('change', function () {
            if (this.checked) {
                $(input).val('').prop('readonly', false).trigger('focus');
            } else {
                $(input).val('0').prop('readonly', true);
            }
        });
    }
    toggleCheckField('#cek_no_rek', '#no_rek');
    toggleCheckField('#cek_no_pk', '#no_pk');

    /* ── Tampilkan daftar nama file yang dipilih di bawah input upload ── */
    function previewFileList(inputSelector, listSelector) {
        $(inputSelector).on('change', function () {
            const list = $(listSelector);
            if (!this.files || this.files.length === 0) {
                list.html('<li class="text-muted fst-italic">Belum ada file dipilih</li>');
                return;
            }
            list.html(Array.from(this.files).map(f => `
                <li><i class="ti ti-paperclip"></i> ${f.name}</li>
            `).join(''));
        });
    }
    previewFileList('#file_ktp', '#preview_file_ktp');
    previewFileList('#file_pk', '#preview_file_pk');

    /* ── Format ribuan saat mengetik plafond ── */
    $('#plafond_kredit').on('input', function () {
        const angka = this.value.replace(/[^\d]/g, '');
        this.value = angka ? ClientHelper.formatNumber(parseInt(angka, 10)) : '';
    });

    /* ── Format tanggal dd-mm-yyyy → yyyy-mm-dd (dibutuhkan API) ── */
    function toIsoDate(val) {
        if (!val) return '';
        const [d, m, y] = val.split('-');
        return `${y}-${m}-${d}`;
    }

    /* ── Simpan ke API ── */
    $('#form-deklarasi').on('submit', async function (e) {
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);

        try {
            const ktpFile = $('#file_ktp')[0].files[0] || null;
            const debtorFiles = Array.from($('#file_pk')[0].files || []);

            const payload = {
                policy_no: '',
                insured_name: $('#nama_debitur').val(),
                nik: $('#no_ktp').val(),
                gender_id: $('#jenis_kelamin').val(),
                birth_place: '',
                birth_date: toIsoDate($('#tanggal_lahir').val()),
                phone_no: $('#no_hp').val(),
                email: $('#email').val(),
                ktp_address: $('#alamat_ktp').val(),
                domicile_address: $('#alamat_domisili').val(),
                debtor_category_id: $('#kategori_debitur').val(),
                company_name: $('#nama_instansi').val(),
                position_name: $('#pangkat_jabatan').val(),
                account_no: $('#no_rek').val(),
                pk_no: $('#no_pk').val(),
                tenor: $('#tenor').val(),
                start_date: toIsoDate($('#periode_awal').val()),
                end_date: (() => {
                    const tenor = parseInt($('#tenor').val(), 10);
                    const periodeAwal = $('#periode_awal').val();
                    if (!tenor || !periodeAwal) return '';
                    const [d, m, y] = periodeAwal.split('-');
                    const akhir = new Date(parseInt(y, 10), parseInt(m, 10) - 1 + tenor, parseInt(d, 10));
                    const pad = n => String(n).padStart(2, '0');
                    return `${akhir.getFullYear()}-${pad(akhir.getMonth() + 1)}-${pad(akhir.getDate())}`;
                })(),
                plafond: $('#plafond_kredit').val().replace(/[^\d]/g, ''),
                rate: $('#rate').val(),
                premium: $('#premium').val().replace(/[^\d]/g, ''),
                ktp_file: ktpFile ? await ClientHelper.fileToDataUri(ktpFile) : null,
                debtor_file: await Promise.all(debtorFiles.map(f => ClientHelper.fileToDataUri(f))),
            };

            const res = await ClientHelper.apiFetch('/api/v1/client/declaration/insert', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const json = await res.json();

            if (!res.ok) {
                ClientHelper.notify(json.message || 'Data gagal disimpan.', res.status === 422 ? 'warning' : 'danger');
                return;
            }

            ClientHelper.notify(json.message || 'Data berhasil disimpan.');
            setTimeout(() => window.location.href = '/client/penutupan/list-data', 1200);
        } catch (err) {
            console.error('Gagal mengirim /declaration/insert:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server. Silakan coba lagi.', 'danger');
        } finally {
            submitBtn.prop('disabled', false);
        }
    });
});
