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

    /** Badge status berwarna. type: success | warning | info | danger */
    statusBadge(status, type) {
        const icons = {
            success: 'ti ti-circle-check',
            warning: 'ti ti-alert-triangle',
            info: 'ti ti-loader',
            danger: 'ti ti-circle-x'
        };
        const icon = icons[type] || icons.info;
        return `<span class="badge-status st-${type || 'info'}"><i class="${icon}"></i>${status}</span>`;
    },

    /**
     * Badge status yang sekaligus tombol menuju halaman detail.
     * Dipakai di kolom status tabel agar tidak perlu kolom aksi terpisah.
     */
    statusLink(status, type, href) {
        const icons = {
            success: 'ti ti-circle-check',
            warning: 'ti ti-alert-triangle',
            info: 'ti ti-loader',
            danger: 'ti ti-circle-x'
        };
        const icon = icons[type] || icons.info;
        return `<a href="${href}" class="badge-status st-${type || 'info'}" title="Klik untuk lihat detail">
                    <i class="${icon}"></i>${status}<i class="ti ti-chevron-right ms-1"></i>
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
