/**
 * ============================================================
 * PAGE: Klaim — Input Data
 * API  : GET  /api/v1/client/claim/asset  → daftar debitur (polis terbit,
 *              belum pernah klaim) & daftar dokumen klaim yang dibutuhkan
 *        POST /api/v1/client/claim/insert → simpan klaim baru
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

let dokumenKlaim = [];

function populateDebtorSelect(debtor) {
    const select = $('#peserta');
    select.empty().append('<option value="">-- Pilih Nama Peserta Asuransi --</option>');

    debtor.forEach(item => {
        select.append(`<option value="${item.declaration_id}">${item.insured_name} (${item.policy_no})</option>`);
    });
}

function renderDokumenTable() {
    const tbody = $('#table-dokumen-klaim tbody');
    tbody.empty();

    dokumenKlaim.forEach((item, index) => {
        const row = $(
            `<tr>
                <td>${index + 1}</td>
                <td>${item.document_name}${item.is_required ? ' <span class="text-danger">*</span>' : ''}</td>
                <td class="doc-file">-</td>
                <td class="doc-status text-center">-</td>
                <td class="doc-date text-center">-</td>
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

function toIsoDate(val) {
    if (!val) return '';
    const [d, m, y] = val.split('-');
    return `${y}-${m}-${d}`;
}

async function loadAsset() {
    try {
        const res = await ClientHelper.apiFetch('/api/v1/client/claim/asset');
        const json = await res.json();

        if (!res.ok) {
            ClientHelper.notify(json.message || 'Gagal memuat data referensi form.', 'warning');
            return;
        }

        populateDebtorSelect(json.data?.debtor || []);
        dokumenKlaim = (json.data?.document || []).map(item => ({
            id: item.id,
            document_name: item.document_name,
            is_required: item.is_required,
            file: null
        }));
        renderDokumenTable();
    } catch (err) {
        console.error('Gagal memuat /claim/asset:', err);
        ClientHelper.notify('Tidak dapat terhubung ke server untuk memuat data referensi form.', 'danger');
    } finally {
        $('#peserta').select2({ theme: 'bootstrap-5', width: '100%' });
    }
}

$(function () {
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const now = new Date();
    $('#tanggal_lapor').text(`${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`);

    document.querySelectorAll('.datepicker').forEach(el => {
        new Datepicker(el, { format: 'dd-mm-yyyy', autohide: true });
    });

    $('#estimasi_klaim').on('input', function () {
        const angka = this.value.replace(/[^\d]/g, '');
        this.value = angka ? ClientHelper.formatNumber(parseInt(angka, 10)) : '';
    });

    loadAsset();

    $(document).on('change', '.document-upload', function () {
        const index = parseInt($(this).data('index'), 10);
        const file = this.files?.[0] || null;
        const row = dokumenKlaim[index];
        if (!row) return;

        row.file = file;

        const tr = $(this).closest('tr');
        tr.find('.doc-status').html(file ? '<i class="ti ti-check text-success"></i>' : '-');
        tr.find('.doc-date').text(file ? formatDateTime(new Date()) : '-');
        tr.find('.doc-file').html(file ? `<span>${file.name}</span>` : '-');
    });

    $('#form-input-data').on('submit', async function (e) {
        e.preventDefault();

        const missingRequired = dokumenKlaim.filter(d => d.is_required && !d.file);
        if (missingRequired.length > 0) {
            ClientHelper.notify(
                'Dokumen wajib berikut belum diunggah: ' + missingRequired.map(d => d.document_name).join(', '),
                'warning'
            );
            return;
        }

        const uploaded = dokumenKlaim.filter(d => d.file);
        if (uploaded.length === 0) {
            ClientHelper.notify('Mohon unggah minimal 1 dokumen klaim.', 'warning');
            return;
        }

        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);

        try {
            const document = await Promise.all(uploaded.map(async d => ({
                document_id: d.id,
                file_name: d.file.name,
                file: await ClientHelper.fileToDataUri(d.file)
            })));

            const payload = {
                declaration_id: $('#peserta').val(),
                incident_date: toIsoDate($('#tanggal_kematian').val()),
                estimated_claim: $('#estimasi_klaim').val().replace(/[^\d]/g, ''),
                description: $('#keterangan').val(),
                document
            };

            const res = await ClientHelper.apiFetch('/api/v1/client/claim/insert', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const json = await res.json();

            if (!res.ok) {
                ClientHelper.notify(json.message || 'Data klaim gagal disimpan.', res.status === 422 ? 'warning' : 'danger');
                return;
            }

            ClientHelper.notify(json.message || 'Data klaim berhasil disimpan.');
            setTimeout(() => window.location.href = '/client/klaim/data', 1200);
        } catch (err) {
            console.error('Gagal mengirim /claim/insert:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server. Silakan coba lagi.', 'danger');
        } finally {
            submitBtn.prop('disabled', false);
        }
    });
});
