/**
 * ============================================================
 * PAGE: Penutupan — List Data
 * Sumber data : ClientData.penutupan
 *
 * Catatan: data di-supply langsung ke DataTables lewat opsi
 * `data` + `columns` (bukan injeksi HTML manual) supaya jumlah
 * kolom selalu cocok dan tidak memicu error DataTables.
 * ============================================================
 */

$(function () {
    const data = ClientData.penutupan;

    $('#table-penutupan').DataTable({
        data: data,
        columns: [
            {
                data: null,
                render: (d, type, row, meta) => meta.row + 1
            },
            { data: 'kategori' },
            {
                data: 'debitur',
                render: d => `<span class="fw-bold">${d}</span>`
            },
            { data: 'tanggalLahir' },
            { data: 'institusi' },
            { data: 'noPk' },
            { data: 'tenor', className: 'text-center' },
            { data: 'periode' },
            {
                data: 'plafondKredit',
                className: 'text-end fw-bold',
                render: d => ClientHelper.formatNumber(d)
            },
            {
                data: 'ratePremi',
                className: 'text-center',
                render: d => d.toFixed(5) + ' %'
            },
            {
                data: 'nilaiPremi',
                className: 'text-end fw-bold',
                render: d => ClientHelper.formatNumber(d)
            },
            {
                data: null,
                render: p => ClientHelper.statusLink(p.status, p.statusType, '/client/penutupan/detail/' + p.id)
            }
        ],
        language: ClientHelper.dataTableLang,
        pageLength: 25,
        ordering: false,
        autoWidth: false
    });

    /* ── Total footer ── */
    const totalPlafond = data.reduce((sum, p) => sum + p.plafondKredit, 0);
    const totalPremi = data.reduce((sum, p) => sum + p.nilaiPremi, 0);
    $('#total-plafond').text(ClientHelper.formatNumber(totalPlafond));
    $('#total-premi').text(ClientHelper.formatNumber(totalPremi));
});
