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
                    ${status} <i class="ti ti-chevron-right"></i>
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
    }
};
