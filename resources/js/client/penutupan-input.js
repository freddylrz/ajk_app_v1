/**
 * ============================================================
 * PAGE: Penutupan — Input Data (Form Deklarasi Reguler Griya)
 * Sumber data : ClientData.master
 * ============================================================
 */

import { ClientData } from './data/dummy-data.js';
import { ClientHelper } from './helpers.js';

$(function () {
    const master = ClientData.master;

    /* ── Isi pilihan dropdown dari data master ── */
    master.kategoriDebitur.forEach(v => $('#kategori_debitur').append(new Option(v, v)));
    master.jenisKelamin.forEach(v => $('#jenis_kelamin').append(new Option(v, v)));

    $('#kategori_debitur, #jenis_kelamin').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    /* ── Datepicker (format dd-mm-yyyy) ── */
    document.querySelectorAll('.datepicker').forEach(el => {
        new Datepicker(el, {
            format: 'dd-mm-yyyy',
            autohide: true,
            language: 'en'
        });
    });

    /* ── Hitung umur otomatis dari tanggal lahir ── */
    $('#tanggal_lahir').on('changeDate change', function () {
        const val = $(this).val(); // dd-mm-yyyy
        if (!val) return;
        const [d, m, y] = val.split('-');
        const umur = ClientHelper.hitungUmur(`${y}-${m}-${d}`);
        $('#umur').val(umur ? umur + ' Tahun' : '');
    });

    /* ── Checkbox No. Rek & No. PK: dicentang = sudah punya nomor (bisa diisi) ── */
    function toggleCheckField(checkbox, input) {
        $(checkbox).on('change', function () {
            if (this.checked) {
                $(input).val('').prop('readonly', false).trigger('focus');
            } else {
                $(input).val('0').prop('readonly', true);
            }
        });
    }
    toggleCheckField('#cek_no_rek', '#no_rek');
    toggleCheckField('#cek_no_pk', '#no_pk');

    /* ── Tampilkan daftar nama file yang dipilih di bawah input upload ── */
    function previewFileList(inputSelector, listSelector) {
        $(inputSelector).on('change', function () {
            const list = $(listSelector);
            if (!this.files || this.files.length === 0) {
                list.html('<li class="text-muted fst-italic">Belum ada file dipilih</li>');
                return;
            }
            list.html(Array.from(this.files).map(f => `
                <li><i class="ti ti-paperclip"></i> ${f.name}</li>
            `).join(''));
        });
    }
    previewFileList('#file_ktp', '#preview_file_ktp');
    previewFileList('#file_pk', '#preview_file_pk');

    /* ── Format ribuan saat mengetik plafond ── */
    $('#plafond_kredit').on('input', function () {
        const angka = this.value.replace(/[^\d]/g, '');
        this.value = angka ? ClientHelper.formatNumber(parseInt(angka, 10)) : '';
    });

    /* ── Hitung premi (simulasi dummy) ── */
    $('#btn-hitung').on('click', function () {
        const tenor = parseInt($('#tenor').val(), 10);
        const periodeAwal = $('#periode_awal').val();
        const plafond = parseInt($('#plafond_kredit').val().replace(/[^\d]/g, ''), 10);

        if (!tenor || !periodeAwal || !plafond) {
            ClientHelper.notify('Mohon lengkapi Tenor, Periode Awal, dan Plafond Kredit terlebih dahulu.', 'warning');
            return;
        }

        // Simulasi rate: makin panjang tenor makin besar rate (dummy)
        const rate = Math.min(2 + (tenor / 24) * 0.85, 9.5);
        const premi = Math.round(plafond * (rate / 100));

        // Periode akhir = periode awal + tenor bulan
        const [d, m, y] = periodeAwal.split('-');
        const akhir = new Date(parseInt(y, 10), parseInt(m, 10) - 1 + tenor, parseInt(d, 10));
        const pad = n => String(n).padStart(2, '0');
        const periodeAkhir = `${pad(akhir.getDate())}-${pad(akhir.getMonth() + 1)}-${akhir.getFullYear()}`;

        $('#output_periode').text(`${periodeAwal} s/d ${periodeAkhir}`);
        $('#output_rate').text(rate.toFixed(5) + ' %');
        $('#output_premi').text(ClientHelper.formatIDR(premi));

        ClientHelper.notify('Perhitungan premi berhasil.');
    });

    /* ── Render tabel Keterangan Kesehatan dari master ── */
    const rowsKesehatan = master.kesehatanQuestions.map(q => `
        <tr data-no="${q.no}" data-trigger="${q.trigger}" ${q.khususWanita ? 'data-khusus-wanita="1"' : ''}>
            <td class="text-center fw-bold">${q.no}</td>
            <td>${q.pertanyaan}</td>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jawaban_${q.no}" id="jawaban_${q.no}_ya" value="YA">
                    <label class="form-check-label" for="jawaban_${q.no}_ya">Ya</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jawaban_${q.no}" id="jawaban_${q.no}_tidak" value="TIDAK">
                    <label class="form-check-label" for="jawaban_${q.no}_tidak">Tidak</label>
                </div>
            </td>
            <td>
                <input type="text" class="form-control" id="keterangan_${q.no}" placeholder="Keterangan">
            </td>
        </tr>
    `).join('');
    $('#tbody-kesehatan').html(rowsKesehatan);

    /* ── Wajibkan Keterangan hanya saat jawaban = trigger pertanyaan ── */
    $('#tbody-kesehatan').on('change', 'input[type="radio"]', function () {
        const row = $(this).closest('tr');
        const trigger = row.data('trigger');
        const keteranganInput = row.find('input[id^="keterangan_"]');
        keteranganInput.prop('required', this.value === trigger);
    });

    /* ── Pertanyaan No. 5 (khusus wanita) menyesuaikan Jenis Kelamin ── */
    $('#jenis_kelamin').on('change', function () {
        const row = $('#tbody-kesehatan tr[data-khusus-wanita="1"]');
        const isPerempuan = $(this).val() === 'Perempuan';

        row.find('input[type="radio"], input[type="text"]').prop('disabled', !isPerempuan);
        if (!isPerempuan) {
            row.find('input[type="radio"]').prop('checked', false).prop('required', false);
            row.find('input[type="text"]').val('-').prop('required', false);
        } else {
            row.find('input[type="text"]').val('');
        }
    });
    $('#jenis_kelamin').trigger('change');

    /* ── Simpan (dummy) ── */
    $('#form-deklarasi').on('submit', function (e) {
        e.preventDefault();

        if ($('#output_premi').text() === '-') {
            ClientHelper.notify('Silakan klik tombol Hitung terlebih dahulu sebelum menyimpan.', 'warning');
            return;
        }

        let kesehatanLengkap = true;
        $('#tbody-kesehatan tr').each(function () {
            if ($(this).find('input[type="radio"]').prop('disabled')) return;
            const terpilih = $(this).find('input[type="radio"]:checked').val();
            if (!terpilih) kesehatanLengkap = false;
        });

        if (!kesehatanLengkap) {
            ClientHelper.notify('Mohon lengkapi seluruh jawaban Keterangan Kesehatan.', 'warning');
            return;
        }

        ClientHelper.notify('Data peserta berhasil disimpan dan menunggu validasi SPV.');
        setTimeout(() => window.location.href = '/client/penutupan/list-data', 1200);
    });
});
