/**
 * ============================================================
 * PAGE: Klaim — Formulir Klaim Reguler
 * API  : belum ada endpoint klaim di sisi server (ClaimController
 *        masih kosong), jadi tabel dibiarkan kosong dan pengiriman
 *        formulir menampilkan info bahwa fitur belum tersedia.
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

$(function () {
    // TODO: sambungkan ke endpoint daftar klaim yang perlu formulir begitu tersedia.
    $('#table-formulir').DataTable({
        data: [],
        columns: [
            { data: null, render: (d, type, row, meta) => meta.row + 1 },
            { data: 'klaimId' },
            { data: 'debitur' },
            { data: 'noPolis' },
            { data: 'cabang' },
            { data: 'tanggalKematian' },
            { data: 'nilaiKlaim', className: 'text-end fw-bold' },
            { data: 'tanggalLapor' },
            { data: 'deskripsi' },
            { data: null },
            { data: null }
        ],
        language: ClientHelper.dataTableLang,
        pageLength: 10,
        ordering: false,
        autoWidth: false
    });

    /* ── Kirim formulir ── */
    $('#form-unggah-formulir').on('submit', function (e) {
        e.preventDefault();
        bootstrap.Modal.getInstance(document.querySelector('#modal-formulir'))?.hide();
        ClientHelper.notify('Fitur unggah formulir klaim belum tersedia di server.', 'warning');
    });
});
