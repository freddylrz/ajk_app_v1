/**
 * ============================================================
 * PAGE: Klaim — Formulir Klaim Reguler
 * Sumber data : ClientData.klaim (filter butuhFormulir = true)
 *
 * Catatan: data di-supply langsung ke DataTables lewat opsi
 * `data` + `columns` (bukan injeksi HTML manual) supaya jumlah
 * kolom selalu cocok dan tidak memicu error DataTables.
 * ============================================================
 */

$(function () {
    const data = ClientData.klaim.filter(k => k.butuhFormulir);

    $('#table-formulir').DataTable({
        data: data,
        columns: [
            {
                data: null,
                render: (d, type, row, meta) => meta.row + 1
            },
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
                render: k => ClientHelper.statusBadge(k.status, k.statusType)
            },
            {
                data: null,
                render: k => `
                    <button type="button" class="btn btn-warning btn-sm btn-isi-formulir"
                            data-klaim-id="${k.klaimId}" data-debitur="${k.debitur}" data-polis="${k.noPolis}">
                        <i class="ti ti-pencil"></i> Isi Formulir
                    </button>`
            }
        ],
        language: ClientHelper.dataTableLang,
        pageLength: 10,
        ordering: false,
        autoWidth: false
    });

    /* ── Buka modal isi formulir (delegated: tetap jalan setelah paging) ── */
    $('#table-formulir').on('click', '.btn-isi-formulir', function () {
        $('#modal-klaim-id').text($(this).data('klaim-id'));
        $('#modal-debitur').text($(this).data('debitur') + ' — Polis: ' + $(this).data('polis'));
        new bootstrap.Modal(document.querySelector('#modal-formulir')).show();
    });

    /* ── Kirim formulir (dummy) ── */
    $('#form-unggah-formulir').on('submit', function (e) {
        e.preventDefault();
        bootstrap.Modal.getInstance(document.querySelector('#modal-formulir')).hide();
        ClientHelper.notify('Formulir klaim ' + $('#modal-klaim-id').text() + ' berhasil dikirim.');
        this.reset();
    });
});
