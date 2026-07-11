/**
 * ============================================================
 * PAGE: Klaim — Detail
 * Sumber data : ClientData.klaim (dicari berdasarkan id
 *               dari atribut data-id yang dikirim controller)
 * ============================================================
 */

$(function () {
    const id = parseInt($('#detail-container').data('id'), 10);
    const k = ClientData.klaim.find(x => x.id === id);

    if (!k) {
        ClientHelper.notify('Data klaim tidak ditemukan.', 'warning');
        setTimeout(() => window.location.href = '/client/klaim/data', 1500);
        return;
    }

    /* ── Header ── */
    $('#head-klaim-id').text(k.klaimId);
    $('#head-status').html(ClientHelper.statusBadge(k.status, k.statusType));

    /* ── Detail ── */
    $('#d-debitur').text(k.debitur);
    $('#d-no-polis').text(k.noPolis);
    $('#d-cabang').text(k.cabang);
    $('#d-tanggal-kematian').text(k.tanggalKematian);
    $('#d-tanggal-lapor').text(k.tanggalLapor);
    $('#d-deskripsi').text(k.deskripsi);
    $('#d-nilai-klaim').text(ClientHelper.formatIDR(k.nilaiKlaim));

    /* ── Dokumen ── */
    $('#d-dokumen').html(k.dokumen.map(f => `
        <li class="mb-2">
            <a href="#!" class="fw-bold" style="font-size:15.5px;">
                <i class="ti ti-file-download"></i> ${f}
            </a>
        </li>
    `).join(''));

    /* ── Log status ── */
    $('#d-log').html(k.logStatus.map(log => `
        <tr>
            <td>${log.no}</td>
            <td>${ClientHelper.statusBadge(log.status, log.no === k.logStatus.length ? k.statusType : 'info')}</td>
            <td>${log.keterangan}</td>
            <td class="fw-bold">${log.tanggal}</td>
        </tr>
    `).join(''));
});
