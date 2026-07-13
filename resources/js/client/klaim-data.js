/**
 * ============================================================
 * PAGE: Klaim — Data Klaim
 * API  : belum ada endpoint klaim di sisi server (ClaimController
 *        masih kosong), jadi tabel dibiarkan kosong.
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

$(function () {
    // TODO: sambungkan ke endpoint daftar klaim begitu tersedia.
    $('#table-klaim').DataTable({
        data: [],
        columns: [
            { data: 'id' },
            { data: 'klaimId' },
            { data: 'debitur' },
            { data: 'noPolis' },
            { data: 'cabang' },
            { data: 'tanggalKematian' },
            { data: 'nilaiKlaim', className: 'text-end fw-bold' },
            { data: 'tanggalLapor' },
            { data: 'deskripsi' },
            { data: null }
        ],
        language: ClientHelper.dataTableLang,
        pageLength: 10,
        ordering: false,
        autoWidth: false
    });
});
