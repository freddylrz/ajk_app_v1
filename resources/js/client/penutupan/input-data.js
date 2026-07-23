import { ClientHelper } from '../shared/helpers.js';

/** Status global modul untuk memastikan premi sesuai dengan versi form terbaru. */
const PREMIUM_CALCULATION_STATE = {
    isCalculated: false,
    formRevision: 0,
    formSignature: null,
};

$(async function () {
    const roles = await ClientHelper.getRoles();
    if (roles.includes('SPV')) {
        ClientHelper.notify('Akses input data tidak tersedia untuk SPV.', 'warning');
        setTimeout(() => window.location.href = '/client/dashboard', 1000);
        return;
    }

    const form = $('#form-deklarasi');
    const floatingActions = document.querySelector('.client-form-actions');
    const footer = document.querySelector('.pc-footer');
    let floatingActionsFrame = null;

    function updateFloatingActionsPosition() {
        floatingActionsFrame = null;

        if (!floatingActions || !footer) return;

        const footerRect = footer.getBoundingClientRect();
        const styles = window.getComputedStyle(floatingActions);
        const gap = parseFloat(styles.getPropertyValue('--client-form-actions-gap')) || 16;
        const footerOverlap = Math.max(0, window.innerHeight - footerRect.top);
        const maxBottom = Math.max(gap, window.innerHeight - floatingActions.offsetHeight - gap);
        const bottom = Math.min(gap + footerOverlap, maxBottom);

        floatingActions.style.setProperty('--client-form-actions-bottom', `${bottom}px`);
    }

    function scheduleFloatingActionsPosition() {
        if (floatingActionsFrame !== null) return;

        floatingActionsFrame = window.requestAnimationFrame(updateFloatingActionsPosition);
    }

    window.addEventListener('scroll', scheduleFloatingActionsPosition, { passive: true });
    window.addEventListener('resize', scheduleFloatingActionsPosition);
    window.addEventListener('load', scheduleFloatingActionsPosition);
    scheduleFloatingActionsPosition();

    function getFormSignature() {
        const fields = form.find('input:not([type="hidden"]), select, textarea').map(function () {
            if (this.type === 'file') {
                return {
                    key: this.id,
                    value: Array.from(this.files || []).map(file => [file.name, file.size, file.lastModified]),
                };
            }

            if (this.type === 'checkbox' || this.type === 'radio') {
                return { key: this.id, value: this.checked };
            }

            return { key: this.id || this.name, value: $(this).val() };
        }).get();

        return JSON.stringify(fields);
    }

    function updatePremiumCalculationUi(isLoading = false) {
        const status = $('#premium-calculation-status');
        const hint = $('#premium-calculation-hint');

        if (isLoading) {
            status.removeClass('bg-success bg-warning text-dark')
                .addClass('bg-info')
                .text('Sedang menghitung');
            hint.text('Mohon tunggu hasil perhitungan premi.');
        } else if (PREMIUM_CALCULATION_STATE.isCalculated) {
            status.removeClass('bg-info bg-warning text-dark')
                .addClass('bg-success')
                .text('Premi sudah dihitung');
            hint.text('Form siap disimpan selama tidak ada data yang diubah.');
        } else {
            status.removeClass('bg-info bg-success')
                .addClass('bg-warning text-dark')
                .text('Premi perlu dihitung');
            hint.text('Hitung kembali premi setelah melengkapi atau mengubah form.');
        }

        $('#btn-simpan').prop('disabled', !PREMIUM_CALCULATION_STATE.isCalculated || isLoading);
    }

    function resetPremiumCalculationResult() {
        $('#output_periode, #output_rate, #output_premi').text('-');
        $('#rate, #premium, #end_date_computed').val('');
    }

    function invalidatePremiumCalculation() {
        PREMIUM_CALCULATION_STATE.formRevision += 1;
        PREMIUM_CALCULATION_STATE.isCalculated = false;
        PREMIUM_CALCULATION_STATE.formSignature = null;
        resetPremiumCalculationResult();
        updatePremiumCalculationUi();
        scheduleFloatingActionsPosition();
    }

    form.on('input change changeDate', 'input:not([type="hidden"]), select, textarea', invalidatePremiumCalculation);
    updatePremiumCalculationUi();

    async function loadAsset() {
        try {
            const res = await ClientHelper.apiFetch('/api/v1/client/declaration/asset');
            const json = await res.json();

            if (!res.ok) {
                ClientHelper.notify(json.message || 'Gagal memuat data referensi form.', 'warning');
                return;
            }

            (json.data?.debt_category || []).forEach(item => {
                $('#kategori_debitur').append(new Option(item.category_name, item.id));
            });

            (json.data?.gender || []).forEach(item => {
                $('#jenis_kelamin').append(new Option(item.name, item.id));
            });
        } catch (err) {
            console.error('Gagal memuat /declaration/asset:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server untuk memuat data referensi form.', 'danger');
        } finally {
            $('#kategori_debitur, #jenis_kelamin').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    }
    loadAsset();

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

    /* ── Format tanggal dd-mm-yyyy → yyyy-mm-dd (dibutuhkan API) ── */
    function toIsoDate(val) {
        if (!val) return '';
        const [d, m, y] = val.split('-');
        return `${y}-${m}-${d}`;
    }

    /* ── Hitung premi: memanggil API premium-calculation ── */
    $('#btn-hitung').on('click', async function () {
        const tenor = parseInt($('#tenor').val(), 10);
        const periodeAwal = $('#periode_awal').val();
        const tanggalLahir = $('#tanggal_lahir').val();
        const plafond = parseInt($('#plafond_kredit').val().replace(/[^\d]/g, ''), 10);

        if (!tanggalLahir) {
            invalidatePremiumCalculation();
            ClientHelper.notify('Mohon lengkapi Tanggal Lahir terlebih dahulu.', 'warning');
            return;
        }

        if (!tenor || !periodeAwal || !plafond) {
            invalidatePremiumCalculation();
            ClientHelper.notify('Mohon lengkapi Tenor, Periode Awal, dan Plafond Kredit terlebih dahulu.', 'warning');
            return;
        }

        const btn = $(this);
        const calculationRevision = PREMIUM_CALCULATION_STATE.formRevision;
        const calculationFormSignature = getFormSignature();

        PREMIUM_CALCULATION_STATE.isCalculated = false;
        PREMIUM_CALCULATION_STATE.formSignature = null;
        resetPremiumCalculationResult();
        updatePremiumCalculationUi(true);
        btn.prop('disabled', true);

        try {
            const res = await ClientHelper.apiFetch('/api/v1/client/declaration/premium-calculation', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    birth_date: toIsoDate(tanggalLahir),
                    start_date: toIsoDate(periodeAwal),
                    tenor: tenor,
                    plafond: plafond
                })
            });
            const json = await res.json();

            if (!res.ok) {
                ClientHelper.notify(json.message || 'Gagal menghitung premi.', 'warning');
                return;
            }

            if (calculationRevision !== PREMIUM_CALCULATION_STATE.formRevision
                || calculationFormSignature !== getFormSignature()) {
                ClientHelper.notify('Form berubah saat premi sedang dihitung. Silakan hitung kembali premi.', 'warning');
                return;
            }

            $('#output_periode').text(`${periodeAwal} s/d ${json.data.end_date}`);
            $('#output_rate').text(json.data.rate + ' ‰');
            $('#output_premi').text('Rp ' + json.data.premium);
            $('#rate').val(json.data.rate);
            $('#premium').val(json.data.premium);
            $('#end_date_computed').val(toIsoDate(json.data.end_date));
            PREMIUM_CALCULATION_STATE.isCalculated = true;
            PREMIUM_CALCULATION_STATE.formSignature = getFormSignature();
            updatePremiumCalculationUi();
        } catch (err) {
            console.error('Gagal memanggil /declaration/premium-calculation:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server.', 'danger');
        } finally {
            btn.prop('disabled', false);
            if (!PREMIUM_CALCULATION_STATE.isCalculated) {
                updatePremiumCalculationUi();
            }
        }
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

    /* ── Simpan ke API ── */
    form.on('submit', async function (e) {
        e.preventDefault();

        if (!PREMIUM_CALCULATION_STATE.isCalculated
            || PREMIUM_CALCULATION_STATE.formSignature !== getFormSignature()
            || !$('#rate').val()
            || !$('#premium').val()) {
            invalidatePremiumCalculation();
            ClientHelper.notify('Form berubah atau premi belum dihitung. Silakan hitung kembali premi sebelum menyimpan.', 'warning');
            return;
        }

        const submitRevision = PREMIUM_CALCULATION_STATE.formRevision;
        const submitBtn = $('#btn-simpan');
        submitBtn.prop('disabled', true);

        try {
            const ktpFile = $('#file_ktp')[0].files[0] || null;
            const debtorFiles = Array.from($('#file_pk')[0].files || []);

            const payload = {
                policy_no: '',
                insured_name: $('#nama_debitur').val(),
                nik: $('#no_ktp').val(),
                gender_id: $('#jenis_kelamin').val(),
                birth_place: '',
                birth_date: toIsoDate($('#tanggal_lahir').val()),
                phone_no: $('#no_hp').val(),
                email: $('#email').val(),
                ktp_address: $('#alamat_ktp').val(),
                domicile_address: $('#alamat_domisili').val(),
                debtor_category_id: $('#kategori_debitur').val(),
                company_name: $('#nama_instansi').val(),
                position_name: $('#pangkat_jabatan').val(),
                account_no: $('#no_rek').val(),
                pk_no: $('#no_pk').val(),
                tenor: $('#tenor').val(),
                start_date: toIsoDate($('#periode_awal').val()),
                end_date: $('#end_date_computed').val(),
                plafond: $('#plafond_kredit').val().replace(/[^\d]/g, ''),
                rate: $('#rate').val(),
                premium: $('#premium').val().replace(/[^\d]/g, ''),
                ktp_file: ktpFile ? await ClientHelper.fileToDataUri(ktpFile) : null,
                debtor_file: await Promise.all(debtorFiles.map(f => ClientHelper.fileToDataUri(f))),
            };

            if (!PREMIUM_CALCULATION_STATE.isCalculated
                || submitRevision !== PREMIUM_CALCULATION_STATE.formRevision
                || PREMIUM_CALCULATION_STATE.formSignature !== getFormSignature()) {
                invalidatePremiumCalculation();
                ClientHelper.notify('Form berubah saat data sedang disiapkan. Silakan hitung kembali premi.', 'warning');
                return;
            }

            const res = await ClientHelper.apiFetch('/api/v1/client/declaration/insert', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const json = await res.json();

            if (!res.ok) {
                ClientHelper.notify(json.message || 'Data gagal disimpan.', res.status === 422 ? 'warning' : 'danger');
                return;
            }

            ClientHelper.notify(json.message || 'Data berhasil disimpan.');
            setTimeout(() => window.location.href = '/client/penutupan/detail/' + json.data.declaration_id, 1200);
        } catch (err) {
            console.error('Gagal mengirim /declaration/insert:', err);
            ClientHelper.notify('Tidak dapat terhubung ke server. Silakan coba lagi.', 'danger');
        } finally {
            submitBtn.prop('disabled', !PREMIUM_CALCULATION_STATE.isCalculated);
        }
    });
});
