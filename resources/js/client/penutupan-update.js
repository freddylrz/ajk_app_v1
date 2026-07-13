/**
 * ============================================================
 * PAGE: Penutupan — Edit Data (revisi deklarasi oleh Operator)
 * API  : GET  /api/v1/client/declaration/asset  → opsi Kategori Debitur & Jenis Kelamin
 *        GET  /api/v1/client/declaration/detail → data existing untuk prefill
 *        POST /api/v1/client/declaration/premium-calculation → hitung ulang premi
 *        POST /api/v1/client/declaration/update → simpan perubahan
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

/* Status Operator boleh mengedit, dan status tujuan setelah disimpan
   (lihat diagram transisi: 1→3, 2→3, 4→5). */
const STATUS_TRANSITION = { 1: 3, 2: 3, 4: 5 };

$(function () {
    const id = $('#form-deklarasi').data('id');
    let existingUpload = { ktp: null, debitur: [] };
    let targetStatusId = null;

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
        const val = $(this).val();
        if (!val) return;
        const [d, m, y] = val.split('-');
        const umur = ClientHelper.hitungUmur(`${y}-${m}-${d}`);
        $('#umur').val(umur ? umur + ' Tahun' : '');
    });

    /* ── Format tanggal dd-mm-yyyy → yyyy-mm-dd (dibutuhkan API) ── */
    function toIsoDate(val) {
        if (!val) return '';
        const [d, m, y] = val.split('-');
        return `${y}-${m}-${d}`;
    }

    /* ── Hitung premi: memanggil API premium-calculation ── */
    $('#btn-hitung').on('click', async function () {
        const tenor = parseInt($('#tenor').val(), 10);
        const periodeAwal = $('#periode_awal').val();
        const tanggalLahir = $('#tanggal_lahir').val();
        const plafond = parseInt($('#plafond_kredit').val().replace(/[^\d]/g, ''), 10);

        if (!tanggalLahir) {
            ClientHelper.notify('Mohon lengkapi Tanggal Lahir terlebih dahulu.', 'warning');
            return;
        }
        if (!tenor || !periodeAwal || !plafond) {
            ClientHelper.notify('Mohon lengkapi Tenor, Periode Awal, dan Plafond Kredit terlebih dahulu.', 'warning');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true);

        try {
            const res = await ClientHelper.apiFetch('/api/v1/client/declaration/premium-calculation', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    birth_date: toIsoDate(tanggalLahir),
                    start_date: toIsoDate(periodeAwal),
                    tenor: tenor,
                    plafond: plafond
                })
            });
            const json = await res.json();

            if (!res.ok) {
                ClientHelper.notify(json.message || 'Gagal menghitung premi.', 'warning');
                return;
            }

            $('#output_periode').text(`${periodeAwal} s/d ${json.data.end_date}`);
            $('#output_rate').text(json.data.rate + ' ‰');
            $('#output_premi').text('Rp ' + json.data.premium);
            $('#rate').val(json.data.rate);
            $('#premium').val(json.data.premium);
            $('#end_date_computed').val(toIsoDate(json.data.end_date));
        } catch (err) {
            console.error('Gagal memanggil /declaration/premium-calculation:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server.', 'danger');
        } finally {
            btn.prop('disabled', false);
        }
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

    /* ── Muat opsi Kategori Debitur & Jenis Kelamin, lalu prefill data existing ── */
    async function loadAssetAndDetail() {
        try {
            const [assetRes, detailRes, roles] = await Promise.all([
                ClientHelper.apiFetch('/api/v1/client/declaration/asset'),
                ClientHelper.apiFetch(`/api/v1/client/declaration/detail?id=${encodeURIComponent(id)}`),
                ClientHelper.getRoles()
            ]);
            const assetJson = await assetRes.json();
            const detailJson = await detailRes.json();

            if (!detailRes.ok) {
                ClientHelper.notify(detailJson.message || 'Gagal memuat data deklarasi.', 'danger');
                setTimeout(() => window.location.href = '/client/penutupan/list-data', 1500);
                return;
            }

            const d = detailJson.data.declaration || {};
            const statusId = parseInt(d.status_id, 10);

            if (!roles.includes('OPR') || !(statusId in STATUS_TRANSITION)) {
                ClientHelper.notify('Data ini tidak dapat diedit oleh akun Anda saat ini.', 'warning');
                setTimeout(() => window.location.href = `/client/penutupan/detail/${id}`, 1500);
                return;
            }
            targetStatusId = STATUS_TRANSITION[statusId];

            if (assetRes.ok) {
                (assetJson.data?.debt_category || []).forEach(item => {
                    $('#kategori_debitur').append(new Option(item.category_name, item.id));
                });
                (assetJson.data?.gender || []).forEach(item => {
                    $('#jenis_kelamin').append(new Option(item.name, item.id));
                });
            }
            $('#kategori_debitur, #jenis_kelamin').select2({ theme: 'bootstrap-5', width: '100%' });

            prefill(d, detailJson.data.upload || {});
        } catch (err) {
            console.error('Gagal memuat data untuk form edit:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server.', 'danger');
        }
    }

    function prefill(d, upload) {
        $('#nama_debitur').val(d.insured_name || '');
        $('#tanggal_lahir').val(d.birth_date || '').trigger('changeDate');
        $('#no_ktp').val(d.nik || '');
        $('#jenis_kelamin').val(d.gender_id || '').trigger('change');
        $('#no_hp').val(d.phone_no || '');
        $('#email').val(d.email || '');
        $('#alamat_ktp').val(d.ktp_address || '');
        $('#alamat_domisili').val(d.domicile_address || '');
        $('#kategori_debitur').val(d.debtor_category_id || '').trigger('change');
        $('#nama_instansi').val(d.company_name || '');
        $('#pangkat_jabatan').val(d.position_name || '');

        const noRek = d.account_no || '0';
        $('#no_rek').val(noRek);
        if (noRek !== '0' && noRek !== '') {
            $('#cek_no_rek').prop('checked', true);
            $('#no_rek').prop('readonly', false);
        }

        const noPk = d.pk_no || '0';
        $('#no_pk').val(noPk);
        if (noPk !== '0' && noPk !== '') {
            $('#cek_no_pk').prop('checked', true);
            $('#no_pk').prop('readonly', false);
        }

        $('#tenor').val(d.tenor || '');
        $('#periode_awal').val(d.start_date || '');
        $('#plafond_kredit').val(d.plafond ? ClientHelper.formatNumber(parseInt(String(d.plafond).replace(/[^\d]/g, ''), 10)) : '');

        if (d.rate && d.premium && d.end_date) {
            $('#output_periode').text(`${d.start_date} s/d ${d.end_date}`);
            $('#output_rate').text(d.rate + ' ‰');
            $('#output_premi').text('Rp ' + d.premium);
            $('#rate').val(d.rate);
            $('#premium').val(d.premium);
            $('#end_date_computed').val(toIsoDate(d.end_date));
        }

        existingUpload = upload;
        if (upload.ktp) {
            $('#current_file_ktp').html(`<li><i class="ti ti-paperclip"></i> Saat ini: <a href="${upload.ktp.file_path}" target="_blank" rel="noopener">${upload.ktp.file_name}</a></li>`);
        }
        if (upload.debitur && upload.debitur.length > 0) {
            $('#current_file_pk').html(upload.debitur.map((f, i) => `
                <li><i class="ti ti-paperclip"></i> Saat ini: <a href="${f.file_path}" target="_blank" rel="noopener">Foto ${i + 1} - ${f.file_name}</a></li>
            `).join(''));
        }
    }

    /* ── Simpan perubahan ke API ── */
    $('#form-deklarasi').on('submit', async function (e) {
        e.preventDefault();

        if (!$('#rate').val() || !$('#premium').val()) {
            ClientHelper.notify('Silakan klik tombol Hitung pada bagian Perhitungan Premi terlebih dahulu.', 'warning');
            return;
        }

        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);

        try {
            const ktpFile = $('#file_ktp')[0].files[0] || null;
            const debtorFiles = Array.from($('#file_pk')[0].files || []);

            const payload = {
                id: id,
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
                end_date: $('#end_date_computed').val(),
                plafond: $('#plafond_kredit').val().replace(/[^\d]/g, ''),
                rate: $('#rate').val(),
                premium: $('#premium').val().replace(/[^\d]/g, ''),
                declaration_status_id: targetStatusId,
                note: $('#note').val() || null,
            };

            if (ktpFile) payload.ktp_file = await ClientHelper.fileToDataUri(ktpFile);
            if (debtorFiles.length > 0) payload.debtor_file = await Promise.all(debtorFiles.map(f => ClientHelper.fileToDataUri(f)));

            const res = await ClientHelper.apiFetch('/api/v1/client/declaration/update', {
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
            setTimeout(() => window.location.href = `/client/penutupan/detail/${id}`, 1200);
        } catch (err) {
            console.error('Gagal mengirim /declaration/update:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server. Silakan coba lagi.', 'danger');
        } finally {
            submitBtn.prop('disabled', false);
        }
    });

    loadAssetAndDetail();
});
