/**
 * ============================================================
 * PAGE: Klaim — Data Klaim
 * Sumber data : ClientData.klaim
 *
 * Catatan: data di-supply langsung ke DataTables lewat opsi
 * `data` + `columns` (bukan injeksi HTML manual) supaya jumlah
 * kolom selalu cocok dan tidak memicu error DataTables.
 * ============================================================
 */

import { ClientData } from './data/dummy-data.js';
import { ClientHelper } from './helpers.js';

$(function () {
    $('#table-klaim').DataTable({
        data: ClientData.klaim,
        columns: [
            { data: 'id' },
            {
                data: 'klaimId',
                render: d => `<span class="fw-bold">${d}</span>`
            },
            {
                data: 'debitur',
                render: d => `<span class="fw-bold">${d}</span>`
            },
            { data: 'noPolis' },
            { data: 'cabang' },
            { data: 'tanggalKematian' },
            {
                data: 'nilaiKlaim',
                className: 'text-end fw-bold',
                render: d => ClientHelper.formatNumber(d)
            },
            { data: 'tanggalLapor' },
            { data: 'deskripsi' },
            {
                data: null,
                render: k => ClientHelper.statusLink(k.status, k.statusType, '/client/klaim/detail/' + k.id)
            }
        ],
        language: ClientHelper.dataTableLang,
        pageLength: 10,
        ordering: false,
        autoWidth: false
    });
});
