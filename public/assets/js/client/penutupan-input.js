/**
 * ============================================================
 * PAGE: Penutupan — Input Data (Form Deklarasi)
 * Sumber data : ClientData.master
 * ============================================================
 */

$(function () {
    const master = ClientData.master;

    /* ── Isi pilihan dropdown dari data master ── */
    master.jenisKelamin.forEach(v => $('#jenis_kelamin').append(new Option(v, v)));
    master.kategoriDebitur.forEach(v => $('#kategori_debitur').append(new Option(v, v)));
    master.institusi.forEach(v => $('#institusi').append(new Option(v, v)));

    $('#jenis_kelamin, #kategori_debitur, #institusi').select2({
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

    /* ── Checkbox No. Rek & No. PK: centang = belum ada (isi 0) ── */
    function toggleCheckField(checkbox, input) {
        $(checkbox).on('change', function () {
            if (this.checked) {
                $(input).val('0').prop('readonly', true);
            } else {
                $(input).val('').prop('readonly', false).trigger('focus');
            }
        });
    }
    toggleCheckField('#cek_no_rek', '#no_rek');
    toggleCheckField('#cek_no_pk', '#no_pk');

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
        const rate = Math.min(1.5 + (tenor / 24) * 0.75, 9.5);
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

    /* ── Simpan (dummy) ── */
    $('#form-deklarasi').on('submit', function (e) {
        e.preventDefault();

        if ($('#output_premi').text() === '-') {
            ClientHelper.notify('Silakan klik tombol Hitung terlebih dahulu sebelum menyimpan.', 'warning');
            return;
        }

        ClientHelper.notify('Data peserta berhasil disimpan dan menunggu validasi SPV.');
        setTimeout(() => window.location.href = '/client/penutupan/list-data', 1200);
    });
});
