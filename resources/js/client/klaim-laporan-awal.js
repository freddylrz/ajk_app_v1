/**
 * ============================================================
 * PAGE: Klaim — Laporan Awal Klaim
 * API  : belum ada endpoint klaim di sisi server (ClaimController
 *        masih kosong), jadi dropdown peserta dibiarkan kosong dan
 *        pengiriman form menampilkan info bahwa fitur belum tersedia.
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

$(function () {
    /* ── Tanggal lapor = hari ini ── */
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const now = new Date();
    $('#tanggal_lapor').text(`${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`);

    /* ── Dropdown peserta asuransi ── */
    // TODO: sambungkan ke endpoint daftar peserta asuransi begitu tersedia.
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

    /* ── Kirim ── */
    $('#form-laporan-awal').on('submit', function (e) {
        e.preventDefault();
        ClientHelper.notify('Fitur pelaporan klaim belum tersedia di server.', 'warning');
    });
});
