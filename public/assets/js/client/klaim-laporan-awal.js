/**
 * ============================================================
 * PAGE: Klaim — Laporan Awal Klaim
 * Sumber data : ClientData.pesertaAsuransi
 * ============================================================
 */

$(function () {
    /* ── Tanggal lapor = hari ini ── */
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const now = new Date();
    $('#tanggal_lapor').text(`${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`);

    /* ── Dropdown peserta asuransi ── */
    ClientData.pesertaAsuransi.forEach(p => {
        $('#peserta').append(new Option(`${p.nama} — Polis: ${p.noPolis}`, p.id));
    });
    $('#peserta').select2({ theme: 'bootstrap-5', width: '100%' });

    /* ── Datepicker ── */
    document.querySelectorAll('.datepicker').forEach(el => {
        new Datepicker(el, { format: 'dd-mm-yyyy', autohide: true });
    });

    /* ── Format ribuan estimasi klaim ── */
    $('#estimasi_klaim').on('input', function () {
        const angka = this.value.replace(/[^\d]/g, '');
        this.value = angka ? ClientHelper.formatNumber(parseInt(angka, 10)) : '';
    });

    /* ── Kirim (dummy) ── */
    $('#form-laporan-awal').on('submit', function (e) {
        e.preventDefault();
        ClientHelper.notify('Laporan awal klaim berhasil dikirim. Tim kami akan segera memproses.');
        setTimeout(() => window.location.href = '/client/klaim/data', 1200);
    });
});
