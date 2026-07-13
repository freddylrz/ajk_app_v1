/**
 * ============================================================
 * HELPERS — CLIENT
 * Fungsi bantu umum yang dipakai lintas halaman client.
 * ============================================================
 */

export const ClientHelper = {

    /** Format angka jadi format ribuan: 1000000 → "1,000,000" */
    formatNumber(value) {
        return new Intl.NumberFormat('en-US').format(value ?? 0);
    },

    /** Format angka jadi rupiah: 1000000 → "IDR 1,000,000" */
    formatIDR(value) {
        return 'IDR ' + this.formatNumber(value);
    },

    /** Badge status berwarna. type: success | warning | info | danger — sama seperti pola badge admin (badge f-14 bg-*) */
    statusBadge(status, type) {
        const bg = {
            success: 'bg-success',
            warning: 'bg-warning',
            info: 'bg-info',
            danger: 'bg-danger'
        };
        return `<span class="badge f-14 ${bg[type] || bg.info}">${status}</span>`;
    },

    /**
     * Badge status yang sekaligus tombol menuju halaman detail.
     * Dipakai di kolom status tabel agar tidak perlu kolom aksi terpisah.
     */
    statusLink(status, type, href) {
        const bg = {
            success: 'bg-success',
            warning: 'bg-warning',
            info: 'bg-info',
            danger: 'bg-danger'
        };
        return `<a href="${href}" class="badge f-14 ${bg[type] || bg.info}" title="Klik untuk lihat detail">
                    ${status}
                </a>`;
    },

    /** Konfigurasi bahasa Indonesia untuk DataTables */
    dataTableLang: {
        search: 'Cari:',
        lengthMenu: 'Tampilkan _MENU_ data',
        info: 'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
        infoEmpty: 'Tidak ada data',
        infoFiltered: '(disaring dari _MAX_ total data)',
        zeroRecords: 'Data tidak ditemukan',
        emptyTable: 'Belum ada data',
        paginate: {
            first: 'Awal',
            last: 'Akhir',
            next: 'Berikutnya',
            previous: 'Sebelumnya'
        }
    },

    /** Ambil query param dari URL, mis. ClientHelper.getParam('id') */
    getParam(name) {
        return new URLSearchParams(window.location.search).get(name);
    },

    /**
     * Ambil daftar role user yang login, mis. ["OPR"] atau ["SPV"],
     * dari GET /api/v1/auth/user-info. Dipakai untuk menampilkan/menyembunyikan
     * aksi Edit (khusus OPR) & Validasi (khusus SPV) di halaman detail.
     */
    async getRoles() {
        try {
            const res = await this.apiFetch('/api/v1/auth/user-info');
            const json = await res.json();
            if (!res.ok) return [];
            return json.data?.user_info?.roles || [];
        } catch (err) {
            console.error('Gagal memuat /auth/user-info:', err);
            return [];
        }
    },

    /** Ambil nilai cookie berdasarkan nama, mis. ClientHelper.getCookie('__ajk-tib-at') */
    getCookie(name) {
        const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : null;
    },

    /**
     * Panggil endpoint /api/v1/... dengan Bearer token dari cookie
     * __ajk-tib-at. Dekripsi token dilakukan middleware di sisi server.
     */
    async apiFetch(url, options = {}) {
        const token = this.getCookie('__ajk-tib-at');
        return fetch(url, {
            ...options,
            headers: {
                'Accept': 'application/json',
                ...(token ? { 'Authorization': 'Bearer ' + token } : {}),
                ...(options.headers || {})
            }
        });
    },

    /** File (upload) -> data URI base64, format yang diterima Init::decodeFile() di backend */
    fileToDataUri(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => {
                // Selipkan nama file supaya backend bisa mendeteksi nama aslinya
                const result = reader.result.replace(';base64,', `;name=${encodeURIComponent(file.name)};base64,`);
                resolve(result);
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    },

    /** Notifikasi sukses/gagal — modal SweetAlert2 biasa (bukan toast) */
    notify(message, type = 'success') {
        const icons = {
            success: 'success',
            warning: 'warning',
            danger: 'error',
            info: 'info'
        };
        const titles = {
            success: 'Berhasil!',
            warning: 'Perhatian!',
            danger: 'Gagal!',
            info: 'Informasi'
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: icons[type] || 'info',
                title: titles[type] || 'Informasi',
                text: message,
                confirmButtonColor: '#00a651'
            });
        } else {
            alert(message);
        }
    },

    /** Hitung umur dari tanggal lahir (format yyyy-mm-dd) */
    hitungUmur(tanggalLahir) {
        if (!tanggalLahir) return '';
        const lahir = new Date(tanggalLahir);
        const now = new Date();
        let umur = now.getFullYear() - lahir.getFullYear();
        const m = now.getMonth() - lahir.getMonth();
        if (m < 0 || (m === 0 && now.getDate() < lahir.getDate())) umur--;
        return umur;
    },

    /**
     * Tabel DataTables server-side untuk GET /api/v1/client/declaration/list.
     * Dipakai bersama oleh halaman "Dalam Proses" (type=1) dan "Terbit Polis" (type=2)
     * karena keduanya memakai endpoint & kolom respons yang sama, hanya beda filter type.
     */
    renderDeclarationTable(tableSelector, type) {
        return $(tableSelector).DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            pageLength: 25,
            autoWidth: false,
            language: this.dataTableLang,
            ajax: (params, callback) => {
                const query = new URLSearchParams({
                    type: type,
                    page: Math.floor(params.start / params.length) + 1,
                    limit: params.length
                });
                if (params.search?.value) query.set('keyword', params.search.value);

                this.apiFetch(`/api/v1/client/declaration/list?${query.toString()}`)
                    .then(res => res.json().then(json => ({ ok: res.ok, json })))
                    .then(({ ok, json }) => {
                        if (!ok) {
                            this.notify(json.message || 'Gagal memuat data.', 'warning');
                            callback({ draw: params.draw, recordsTotal: 0, recordsFiltered: 0, data: [] });
                            return;
                        }
                        callback({
                            draw: params.draw,
                            recordsTotal: json.pagination?.total || 0,
                            recordsFiltered: json.pagination?.total || 0,
                            data: json.data || []
                        });
                    })
                    .catch(err => {
                        console.error('Gagal memuat /declaration/list:', err);
                        this.notify('Tidak dapat terhubung ke server.', 'danger');
                        callback({ draw: params.draw, recordsTotal: 0, recordsFiltered: 0, data: [] });
                    });
            },
            columns: [
                { data: null, orderable: false, render: (d, t, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { data: 'declaration_no', defaultContent: '-' },
                { data: 'policy_no', defaultContent: '-' },
                { data: 'insured_name', defaultContent: '-' },
                { data: 'nik', defaultContent: '-' },
                { data: 'gender_desc', defaultContent: '-' },
                { data: 'birth_date', defaultContent: '-' },
                { data: 'plafond', className: 'text-end fw-bold', defaultContent: '-' },
                { data: 'created_at', defaultContent: '-' },
                {
                    data: 'status_name',
                    orderable: false,
                    render: (data, t, row) => this.statusLink(data || '-', 'info', `/client/penutupan/detail/${row.id}`)
                }
            ]
        });
    }
};
