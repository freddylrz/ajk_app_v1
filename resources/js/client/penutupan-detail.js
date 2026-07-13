/**
 * ============================================================
 * PAGE: Penutupan — Detail (Reguler Griya)
 * Sumber data : ClientData.penutupan (dicari berdasarkan id
 *               dari atribut data-id yang dikirim controller)
 * ============================================================
 */

import { ClientData } from './data/dummy-data.js';
import { ClientHelper } from './helpers.js';

$(function () {
    const id = parseInt($('#detail-container').data('id'), 10);
    const p = ClientData.penutupan.find(x => x.id === id);
    const questions = ClientData.master.kesehatanQuestions;

    if (!p) {
        ClientHelper.notify('Data penutupan tidak ditemukan.', 'warning');
        setTimeout(() => window.location.href = '/client/penutupan/list-data', 1500);
        return;
    }

    /* ── Header ── */
    $('#head-no-polis').text(p.noPolis);
    $('#head-status').html(ClientHelper.statusBadge(p.status, p.statusType));

    /* ── Data diri ── */
    $('#d-kategori-debitur').text(p.kategoriDebitur);
    $('#d-debitur').text(p.debitur);
    $('#d-tanggal-lahir').text(p.tanggalLahir);
    $('#d-umur').text(p.umur);
    $('#d-no-ktp').text(p.noKtp);
    $('#d-jenis-kelamin').text(p.jenisKelamin);
    $('#d-no-hp').text(p.noHp);
    $('#d-email').text(p.email || '-');

    /* ── Instansi & pinjaman ── */
    $('#d-instansi').text(p.namaInstansi);
    $('#d-pangkat').text(p.pangkatJabatan);
    $('#d-no-rek').text(p.noRek);
    $('#d-no-pk').text(p.noPk);
    $('#d-tenor').text(p.tenor + ' Bulan');
    $('#d-input-date').text(p.inputDate);
    $('#d-periode').text(p.periode);

    /* ── Nilai & alamat ── */
    $('#d-plafond').text(ClientHelper.formatIDR(p.plafondKredit));
    $('#d-rate').text(p.ratePremi.toFixed(5) + ' %');
    $('#d-premi').text(ClientHelper.formatIDR(p.nilaiPremi));
    $('#d-alamat-ktp').text(p.alamatKtp);
    $('#d-alamat-domisili').text(p.alamatDomisili);

    /* ── Dokumen ── */
    $('#d-files').html(p.files.map(f => `
        <li class="mb-2">
            <a href="#!" class="fw-bold" style="font-size:15.5px;">
                <i class="ti ti-file-download"></i> ${f}
            </a>
        </li>
    `).join(''));

    /* ── Keterangan Kesehatan ── */
    $('#d-kesehatan').html(p.kesehatan.map(k => {
        const q = questions.find(item => item.no === k.no);
        return `
            <tr>
                <td class="text-center fw-bold">${k.no}</td>
                <td>${q ? q.pertanyaan : '-'}</td>
                <td class="text-center fw-bold">${k.jawaban}</td>
                <td>${k.keterangan}</td>
            </tr>
        `;
    }).join(''));

    /* ── Log status ── */
    $('#d-log').html(p.logStatus.map(log => `
        <tr>
            <td>${log.no}</td>
            <td>${ClientHelper.statusBadge(log.status, log.no === p.logStatus.length ? p.statusType : 'info')}</td>
            <td>${log.keterangan}</td>
            <td class="fw-bold">${log.tanggal}</td>
        </tr>
    `).join(''));
});
