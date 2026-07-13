/**
 * ============================================================
 * PAGE: Penutupan — List Data
 * API  : GET /api/v1/client/declaration/list — belum diimplementasikan
 *        di sisi server, jadi tabel dibiarkan kosong sampai endpoint
 *        tersedia (tidak diisi data bikinan).
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

$(function () {
    // TODO: sambungkan ke GET /api/v1/client/declaration/list begitu tersedia.
    $('#table-penutupan').DataTable({
        data: [],
        columns: [
            { data: null, render: (d, type, row, meta) => meta.row + 1 },
            { data: 'kategoriDebitur' },
            { data: 'debitur' },
            { data: 'tanggalLahir' },
            { data: 'namaInstansi' },
            { data: 'noPk' },
            { data: 'tenor', className: 'text-center' },
            { data: 'periode' },
            { data: 'plafondKredit', className: 'text-end fw-bold' },
            { data: 'ratePremi', className: 'text-center' },
            { data: 'nilaiPremi', className: 'text-end fw-bold' },
            { data: null }
        ],
        language: ClientHelper.dataTableLang,
        pageLength: 25,
        ordering: false,
        autoWidth: false
    });

    $('#total-plafond').text('-');
    $('#total-premi').text('-');
});
