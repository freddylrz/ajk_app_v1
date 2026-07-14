/**
 * ============================================================
 * PAGE: Penutupan — Detail (Reguler Griya)
 * API  : GET  /api/v1/client/declaration/detail?id=...
 *        POST /api/v1/client/declaration/validation (khusus SPV, status=3)
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

/* Status yang boleh diedit Operator (lihat diagram transisi status). */
const OPR_EDITABLE_STATUS = [1, 2, 4];
/* Status yang menunggu validasi SPV. */
const SPV_VALIDATION_STATUS = 3;

function statusBadgeType(statusId) {
    if (statusId === 7) return 'success';
    if (statusId === 99) return 'danger';
    if (statusId === 3 || statusId === 5) return 'info';
    return 'warning';
}

$(function () {
    const id = $('#detail-container').data('id');

    async function load() {
        try {
            const [detailRes, roles] = await Promise.all([
                ClientHelper.apiFetch(`/api/v1/client/declaration/detail?id=${encodeURIComponent(id)}`),
                ClientHelper.getRoles()
            ]);
            const json = await detailRes.json();

            if (!detailRes.ok) {
                ClientHelper.notify(json.message || 'Gagal memuat data deklarasi.', 'warning');
                return;
            }

            render(json.data, roles);
        } catch (err) {
            console.error('Gagal memuat /declaration/detail:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server.', 'danger');
        }
    }

    function render(data, roles) {
        const d = data.declaration || {};
        const upload = data.upload || {};
        const logs = data.logs || [];

        $('#head-no-polis').text(d.policy_no || d.declaration_no || '-');
        $('#head-status').html(ClientHelper.statusBadge(d.status_name || '-', statusBadgeType(d.status_id)));

        $('#d-kategori-debitur').text(d.debtor_category_name || '-');
        $('#d-debitur').text(d.insured_name || '-');
        $('#d-tanggal-lahir').text(d.birth_date || '-');
        $('#d-umur').text(d.birth_date ? ClientHelper.hitungUmur(toIsoDate(d.birth_date)) + ' Tahun' : '-');
        $('#d-no-ktp').text(d.nik || '-');
        $('#d-jenis-kelamin').text(d.gender_desc || '-');
        $('#d-no-hp').text(d.phone_no || '-');
        $('#d-email').text(d.email || '-');

        $('#d-instansi').text(d.company_name || '-');
        $('#d-pangkat').text(d.position_name || '-');
        $('#d-no-rek').text(d.account_no || '-');
        $('#d-no-pk').text(d.pk_no || '-');
        $('#d-tenor').text(d.tenor ? d.tenor + ' Bulan' : '-');
        $('#d-periode-awal').text(d.start_date || '-');
        $('#d-periode-akhir').text(d.end_date || '-');

        $('#d-plafond').text(d.plafond ? 'Rp ' + d.plafond : '-');
        $('#d-rate').text(d.rate ? d.rate + ' ‰' : '-');
        $('#d-premi').text(d.premium ? 'Rp ' + d.premium : '-');
        $('#d-alamat-ktp').text(d.ktp_address || '-');
        $('#d-alamat-domisili').text(d.domicile_address || '-');

        renderFiles(upload);
        renderLogs(logs);
        setupActions(d, roles);
    }

    function renderFiles(upload) {
        const files = [];
        if (upload.ktp) files.push({ label: 'Foto KTP', ...upload.ktp });
        (upload.debitur || []).forEach((f, i) => files.push({ label: `Foto Debitur ${i + 1}`, ...f }));

        if (files.length === 0) {
            $('#d-files').html('<li class="text-muted fst-italic">Belum ada file diunggah</li>');
            return;
        }

        $('#d-files').html(files.map(f => `
            <li><i class="ti ti-paperclip"></i> <a href="${f.file_path}" target="_blank" rel="noopener">${f.label}: ${f.file_name}</a></li>
        `).join(''));
    }

    function renderLogs(logs) {
        if (logs.length === 0) {
            $('#d-log').html('<tr><td colspan="4" class="text-center text-muted fst-italic">Belum ada log status</td></tr>');
            return;
        }
        $('#d-log').html(logs.map((l, i) => `
            <tr>
                <td class="text-center">${i + 1}</td>
                <td>${l.status_name || '-'}</td>
                <td>${l.note || '-'}</td>
                <td>${l.log_date || '-'}</td>
            </tr>
        `).join(''));
    }

    function setupActions(d, roles) {
        const isOpr = roles.includes('OPR');
        const isSpv = roles.includes('SPV');
        const statusId = parseInt(d.status_id, 10);

        if (isOpr && OPR_EDITABLE_STATUS.includes(statusId)) {
            $('#btn-edit').attr('href', `/client/penutupan/update/${d.id}`).removeClass('d-none');
        }

        if (isSpv && statusId === SPV_VALIDATION_STATUS) {
            $('#area-validasi-spv').removeClass('d-none');
        }
    }

    function toIsoDate(val) {
        if (!val) return '';
        const [dd, mm, yyyy] = val.split('-');
        return `${yyyy}-${mm}-${dd}`;
    }

    async function sendValidation(statusId, requireNote) {
        const note = $('#catatan_validasi').val().trim();

        if (requireNote && !note) {
            ClientHelper.notify('Catatan wajib diisi jika data dikembalikan ke Operator.', 'warning');
            return;
        }

        const confirm = await Swal.fire({
            title: 'Konfirmasi',
            text: statusId === 5
                ? 'Setujui dan teruskan deklarasi ini ke TuguBro?'
                : 'Kembalikan deklarasi ini ke Operator untuk direvisi?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });
        if (!confirm.isConfirmed) return;

        try {
            const res = await ClientHelper.apiFetch('/api/v1/client/declaration/validation', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, status_id: statusId, note: note || null })
            });
            const json = await res.json();

            if (!res.ok) {
                ClientHelper.notify(json.message || 'Gagal memproses validasi.', 'warning');
                return;
            }

            ClientHelper.notify(json.message || 'Berhasil diproses.');
            setTimeout(() => window.location.reload(), 1200);
        } catch (err) {
            console.error('Gagal memanggil /declaration/validation:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server.', 'danger');
        }
    }

    $('#btn-setujui').on('click', () => sendValidation(5, false));
    $('#btn-kembalikan').on('click', () => sendValidation(2, true));

    load();
});
