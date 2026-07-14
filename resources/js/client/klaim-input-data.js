import { ClientHelper } from './helpers.js';

const apiResponse = {
    status: 200,
    message: 'Success.',
    data: {
        debtor: [
            {
                declaration_id: '2607141245365680471028',
                policy_no: 'PL-202607-587581',
                insured_name: 'Lorenzo'
            }
        ],
        document: [
            { id: 1, document_name: 'Surat Permohonan Klaim', is_required: true },
            { id: 2, document_name: 'Fotocopy Identitas', is_required: true },
            { id: 3, document_name: 'Copy Perjanjian Kredit', is_required: true },
            { id: 4, document_name: 'Copy Rekening Koran', is_required: true },
            { id: 5, document_name: 'Loan Inquiry', is_required: true },
            { id: 6, document_name: 'Copy Nilai Pinjaman Pelunasan', is_required: true },
            { id: 7, document_name: 'Jadwal Angsuran', is_required: true },
            { id: 8, document_name: 'Surat Keterangan Kematian', is_required: true },
            { id: 9, document_name: 'Surat Keterangan Kepolisian', is_required: true },
            { id: 10, document_name: 'Kronologis Kematian Ahli Waris', is_required: true },
            { id: 11, document_name: 'Surat Keterangan Rumah Sakit', is_required: true }
        ]
    }
};

const dokumenKlaim = apiResponse.data.document.map(item => ({
    id: item.id,
    document_name: item.document_name,
    is_required: item.is_required,
    uploaded: false,
    fileName: null,
    link: '#',
    date: null
}));

function populateDebtorSelect() {
    const select = $('#peserta');
    select.empty().append('<option value="">-- Pilih Nama Peserta Asuransi --</option>');

    apiResponse.data.debtor.forEach(debtor => {
        select.append(`<option value="${debtor.declaration_id}">${debtor.insured_name} (${debtor.policy_no})</option>`);
    });
}

function renderDokumenTable() {
    const tbody = $('#table-dokumen-klaim tbody');
    tbody.empty();

    dokumenKlaim.forEach((item, index) => {
        const row = $(
            `<tr>
                <td>${index + 1}</td>
                <td>${item.document_name}</td>
                <td class="doc-file">${item.fileName ? `<a href="${item.link}" class="text-decoration-none">${item.fileName}</a>` : '-'}</td>
                <td class="doc-status text-center">${item.uploaded ? '<i class="ti ti-check text-success"></i>' : '-'}</td>
                <td class="doc-date text-center">${item.date || '-'}</td>
                <td class="doc-upload">
                    <input type="file" class="form-control form-control-sm document-upload" data-index="${index}" accept="application/pdf,image/*" />
                </td>
            </tr>`
        );

        tbody.append(row);
    });
}

function formatDateTime(date) {
    const dd = String(date.getDate()).padStart(2, '0');
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const yyyy = date.getFullYear();
    const hh = String(date.getHours()).padStart(2, '0');
    const min = String(date.getMinutes()).padStart(2, '0');
    return `${dd}/${mm}/${yyyy} ${hh}:${min}`;
}

$(function () {
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const now = new Date();
    $('#tanggal_lapor').text(`${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`);

    $('#peserta').select2({ theme: 'bootstrap-5', width: '100%' });

    document.querySelectorAll('.datepicker').forEach(el => {
        new Datepicker(el, { format: 'dd-mm-yyyy', autohide: true });
    });

    $('#estimasi_klaim').on('input', function () {
        const angka = this.value.replace(/[^\d]/g, '');
        this.value = angka ? ClientHelper.formatNumber(parseInt(angka, 10)) : '';
    });

    populateDebtorSelect();
    renderDokumenTable();

    $(document).on('change', '.document-upload', function () {
        const index = parseInt($(this).data('index'), 10);
        const fileName = this.files?.[0]?.name || null;

        if (Number.isInteger(index)) {
            const row = dokumenKlaim[index];
            if (!row) return;

            row.uploaded = !!fileName;
            row.fileName = fileName || row.fileName || null;
            row.link = '#';
            row.date = fileName ? formatDateTime(new Date()) : null;

            const tr = $(this).closest('tr');
            tr.find('.doc-status').html(row.uploaded ? '<i class="ti ti-check text-success"></i>' : '-');
            tr.find('.doc-date').text(row.date || '-');
            tr.find('.doc-file').html(row.fileName ? `<span>${row.fileName}</span>` : '-');
        }
    });

    $('#form-input-data').on('submit', function (e) {
        e.preventDefault();
        ClientHelper.notify('Fitur input data klaim belum tersedia di server.', 'warning');
    });
});
