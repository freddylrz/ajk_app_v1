import { ClientHelper } from '../shared/helpers.js';

$(function () {

    /* ── Datepicker (format dd-mm-yyyy) ── */
    document.querySelectorAll('.datepicker').forEach(el => {
        new Datepicker(el, {
            format: 'dd-mm-yyyy',
            autohide: true,
            language: 'en'
        });
    });

    /* ── Format tanggal dd-mm-yyyy → yyyy-mm-dd (dibutuhkan API) ── */
    function toIsoDate(val) {
        if (!val) return '';
        const [d, m, y] = val.split('-');
        return `${y}-${m}-${d}`;
    }

    /* ── Format ribuan saat mengetik plafond ── */
    $('#plafond_kredit').on('input', function () {
        const angka = this.value.replace(/[^\d]/g, '');
        this.value = angka ? ClientHelper.formatNumber(parseInt(angka, 10)) : '';
    });

    /* ── Hitung simulasi premi: memanggil API premium-calculation ── */
    $('#btn-hitung').on('click', async function () {
        const tanggalLahir = $('#tanggal_lahir').val();
        const periodeAwal = $('#periode_awal').val();
        const tenor = parseInt($('#tenor').val(), 10);
        const plafond = parseInt($('#plafond_kredit').val().replace(/[^\d]/g, ''), 10);

        if (!tanggalLahir || !periodeAwal || !tenor || !plafond) {
            ClientHelper.notify('Mohon lengkapi Tanggal Lahir, Periode Awal, Tenor, dan Plafond Kredit terlebih dahulu.', 'warning');
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

            const umur = ClientHelper.hitungUmur(toIsoDate(tanggalLahir));

            $('#output_umur').text(umur !== '' ? umur + ' Tahun' : '-');
            $('#output_periode').text(`${periodeAwal} s/d ${json.data.end_date}`);
            $('#output_rate').text(json.data.rate + ' ‰');
            $('#output_premi').text('Rp ' + json.data.premium);
        } catch (err) {
            console.error('Gagal memanggil /declaration/premium-calculation:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server.', 'danger');
        } finally {
            btn.prop('disabled', false);
        }
    });
});
