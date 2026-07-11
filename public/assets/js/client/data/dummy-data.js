/**
 * ============================================================
 * DUMMY DATA — CLIENT
 * ------------------------------------------------------------
 * Semua data sementara untuk halaman client disimpan di sini
 * dalam format JSON, seakan-akan hasil respons API.
 *
 * Nanti saat integrasi backend: hapus file ini dan ganti
 * pemakaian `ClientData.xxx` dengan panggilan API (axios/fetch).
 * ============================================================
 */

const ClientData = {

    /* ── Info user login ─────────────────────────────────── */
    "user": {
        "username": "OPR",
        "kantor": "KC KUNINGAN",
        "cabang": "BNI Cabang KC KUNINGAN",
        "role": "client"
    },

    /* ── Ringkasan dashboard ─────────────────────────────── */
    "dashboard": {
        "totalPertanggungan": 10000000000,
        "totalPremi": 100000000,
        "totalDebitur": 500,
        "totalKlaim": 7,
        "klaimDiproses": 3,
        "klaimSelesai": 4,
        "penutupanPerBulan": [
            { "bulan": "Jan", "jumlah": 32 },
            { "bulan": "Feb", "jumlah": 41 },
            { "bulan": "Mar", "jumlah": 38 },
            { "bulan": "Apr", "jumlah": 55 },
            { "bulan": "Mei", "jumlah": 47 },
            { "bulan": "Jun", "jumlah": 62 },
            { "bulan": "Jul", "jumlah": 28 }
        ]
    },

    /* ── Master pilihan form ─────────────────────────────── */
    "master": {
        "jenisKelamin": ["Laki-laki", "Perempuan"],
        "kategoriDebitur": ["Pensiunan", "Calon Pensiunan", "Pra Pensiun"],
        "institusi": [
            "Pensiunan PNS",
            "Calon Pensiunan PNS",
            "Pensiunan TNI/POLRI",
            "Pensiunan BUMN",
            "Janda/Duda Pensiunan"
        ]
    },

    /* ── Data penutupan (list data) ──────────────────────── */
    "penutupan": [
        {
            "id": 1,
            "kategori": "NON Grace Period",
            "kantor": "KC KUNINGAN",
            "debitur": "DEDI SUBANDI",
            "tanggalLahir": "29/10/1963",
            "umur": "62 Tahun",
            "noKtp": "3210120709630001",
            "jenisKelamin": "Laki-laki",
            "alamat": "JL. SILIWANGI NO. 12 RT 001 RW 003 KEL. PURWAWINANGUN KEC. KUNINGAN KAB. KUNINGAN",
            "kategoriDebitur": "Calon Pensiunan",
            "institusi": "Calon Pensiunan PNS",
            "noRek": "0",
            "noPk": "103/KNG/PK-FLEKSI PENSIUN/2021",
            "tenor": 48,
            "periode": "16/04/2021 - 16/04/2025",
            "plafondKredit": 100000000,
            "ratePremi": 1.9,
            "nilaiPremi": 1900000,
            "status": "REVISI INPUT DATA DEBITUR",
            "statusType": "warning",
            "inputDate": "16-04-2021 09:12",
            "noPolis": "015920210416009900",
            "files": ["ktp-dedi.jpeg", "ttd-pk-dedi.jpeg", "berdiri-depan-dedi.jpeg", "berdiri-samping-dedi.jpeg"],
            "logStatus": [
                { "no": 1, "status": "MENUNGGU VALIDASI SPV", "keterangan": "-", "tanggal": "16-04-2021 09:12" },
                { "no": 2, "status": "REVISI INPUT DATA DEBITUR", "keterangan": "Nomor rekening pinjaman belum diisi", "tanggal": "17-04-2021 10:30" }
            ]
        },
        {
            "id": 2,
            "kategori": "NON Grace Period",
            "kantor": "KC KUNINGAN",
            "debitur": "ACENG JUNANTA",
            "tanggalLahir": "07/09/1965",
            "umur": "58 Tahun",
            "noKtp": "3210120709650001",
            "jenisKelamin": "Laki-laki",
            "alamat": "BLOK SABTU RT 002 RW 002 DESA GANDU KEC DAWUAN KAB MAJALENGKA",
            "kategoriDebitur": "Calon Pensiunan",
            "institusi": "Calon Pensiunan PNS",
            "noRek": "0",
            "noPk": "0",
            "tenor": 180,
            "periode": "20/06/2024 - 20/06/2039",
            "plafondKredit": 418000000,
            "ratePremi": 7.1,
            "nilaiPremi": 29678000,
            "status": "MENUNGGU VALIDASI SPV",
            "statusType": "info",
            "inputDate": "21-06-2024 16:39",
            "noPolis": "-",
            "files": ["66754a4f3d9c8.jpeg", "66754a4f3ee4b.jpeg", "66754a4f4063b.jpeg", "66754a4f41f09.jpeg"],
            "logStatus": [
                { "no": 1, "status": "MENUNGGU VALIDASI SPV", "keterangan": "-", "tanggal": "21-06-2024 16:39" }
            ]
        },
        {
            "id": 3,
            "kategori": "Grace Period",
            "kantor": "KC KUNINGAN",
            "debitur": "SITI RAHAYU",
            "tanggalLahir": "15/02/1962",
            "umur": "64 Tahun",
            "noKtp": "3210125502620002",
            "jenisKelamin": "Perempuan",
            "alamat": "JL. RAYA CILIMUS NO. 45 RT 003 RW 001 KEC. CILIMUS KAB. KUNINGAN",
            "kategoriDebitur": "Pensiunan",
            "institusi": "Pensiunan PNS",
            "noRek": "1234567890",
            "noPk": "211/KNG/PK-FLEKSI PENSIUN/2024",
            "tenor": 120,
            "periode": "05/01/2025 - 05/01/2035",
            "plafondKredit": 250000000,
            "ratePremi": 5.25,
            "nilaiPremi": 13125000,
            "status": "POLIS TERBIT",
            "statusType": "success",
            "inputDate": "05-01-2025 11:05",
            "noPolis": "015920250105112233",
            "files": ["ktp-siti.jpeg", "ttd-pk-siti.jpeg", "berdiri-depan-siti.jpeg", "berdiri-samping-siti.jpeg"],
            "logStatus": [
                { "no": 1, "status": "MENUNGGU VALIDASI SPV", "keterangan": "-", "tanggal": "05-01-2025 11:05" },
                { "no": 2, "status": "DIVALIDASI SPV", "keterangan": "Data lengkap", "tanggal": "06-01-2025 08:20" },
                { "no": 3, "status": "POLIS TERBIT", "keterangan": "No. Polis 015920250105112233", "tanggal": "08-01-2025 14:45" }
            ]
        }
    ],

    /* ── Nama peserta untuk dropdown laporan awal klaim ──── */
    "pesertaAsuransi": [
        { "id": 1, "nama": "DEDI SUBANDI", "noPolis": "015920210416009900" },
        { "id": 2, "nama": "ACENG JUNANTA", "noPolis": "-" },
        { "id": 3, "nama": "SITI RAHAYU", "noPolis": "015920250105112233" },
        { "id": 4, "nama": "USMAN", "noPolis": "015920200324755900" },
        { "id": 5, "nama": "ELLY YULIAH", "noPolis": "015920201120241900" }
    ],

    /* ── Data klaim ──────────────────────────────────────── */
    "klaim": [
        {
            "id": 7,
            "klaimId": "CLM.03758",
            "debitur": "USMAN",
            "noPolis": "015920200324755900",
            "cabang": "KC KUNINGAN",
            "tanggalKematian": "01/06/2026",
            "nilaiKlaim": 140329509,
            "tanggalLapor": "09/06/2026",
            "deskripsi": "SAKIT",
            "status": "PROSES VERIFIKASI DOKUMEN DI ASURANSI",
            "statusType": "info",
            "butuhFormulir": true,
            "dokumen": ["laporan-awal-usman.pdf"],
            "logStatus": [
                { "no": 1, "status": "LAPORAN AWAL DITERIMA", "keterangan": "-", "tanggal": "09-06-2026 10:15" },
                { "no": 2, "status": "PROSES VERIFIKASI DOKUMEN DI ASURANSI", "keterangan": "Menunggu kelengkapan formulir klaim", "tanggal": "10-06-2026 09:00" }
            ]
        },
        {
            "id": 6,
            "klaimId": "CLM.03471",
            "debitur": "ELLY YULIAH",
            "noPolis": "015920201120241900",
            "cabang": "KC KUNINGAN",
            "tanggalKematian": "05/01/2026",
            "nilaiKlaim": 159269187,
            "tanggalLapor": "22/01/2026",
            "deskripsi": "SAKIT",
            "status": "PROSES VERIFIKASI DOKUMEN DI ASURANSI",
            "statusType": "info",
            "butuhFormulir": true,
            "dokumen": ["laporan-awal-elly.pdf", "formulir-klaim-elly.pdf"],
            "logStatus": [
                { "no": 1, "status": "LAPORAN AWAL DITERIMA", "keterangan": "-", "tanggal": "22-01-2026 13:40" },
                { "no": 2, "status": "PROSES VERIFIKASI DOKUMEN DI ASURANSI", "keterangan": "-", "tanggal": "25-01-2026 08:30" }
            ]
        },
        {
            "id": 5,
            "klaimId": "CLM.02573",
            "debitur": "ODI AYIP WILAGA",
            "noPolis": "015920200921218800",
            "cabang": "KC KUNINGAN",
            "tanggalKematian": "20/12/2024",
            "nilaiKlaim": 185073073,
            "tanggalLapor": "27/12/2024",
            "deskripsi": "KLAIM MENINGGAL DUNIA",
            "status": "PROSES VERIFIKASI DOKUMEN DI ASURANSI",
            "statusType": "info",
            "butuhFormulir": false,
            "dokumen": ["laporan-awal-odi.pdf", "formulir-klaim-odi.pdf"],
            "logStatus": [
                { "no": 1, "status": "LAPORAN AWAL DITERIMA", "keterangan": "-", "tanggal": "27-12-2024 09:10" },
                { "no": 2, "status": "PROSES VERIFIKASI DOKUMEN DI ASURANSI", "keterangan": "-", "tanggal": "30-12-2024 11:00" }
            ]
        },
        {
            "id": 4,
            "klaimId": "CLM.01734",
            "debitur": "D SUDARJAT",
            "noPolis": "015920200922277200",
            "cabang": "KC KUNINGAN",
            "tanggalKematian": "07/11/2023",
            "nilaiKlaim": 71904799,
            "tanggalLapor": "06/12/2023",
            "deskripsi": "SAKIT",
            "status": "KLAIM SUDAH DIBAYAR (CLOSED FILE)",
            "statusType": "success",
            "butuhFormulir": false,
            "dokumen": ["laporan-awal-sudarjat.pdf", "formulir-klaim-sudarjat.pdf", "bukti-bayar-sudarjat.pdf"],
            "logStatus": [
                { "no": 1, "status": "LAPORAN AWAL DITERIMA", "keterangan": "-", "tanggal": "06-12-2023 14:22" },
                { "no": 2, "status": "PROSES VERIFIKASI DOKUMEN DI ASURANSI", "keterangan": "-", "tanggal": "10-12-2023 09:15" },
                { "no": 3, "status": "KLAIM SUDAH DIBAYAR (CLOSED FILE)", "keterangan": "Pembayaran selesai", "tanggal": "28-12-2023 16:05" }
            ]
        },
        {
            "id": 3,
            "klaimId": "CLM.01219",
            "debitur": "R JUNAEDI",
            "noPolis": "015920200821114600",
            "cabang": "KC KUNINGAN",
            "tanggalKematian": "22/09/2023",
            "nilaiKlaim": 120000000,
            "tanggalLapor": "22/09/2023",
            "deskripsi": "MENINGGAL DUNIA SAKIT",
            "status": "REVISI DOKUMEN OLEH ASURANSI DI BNI",
            "statusType": "warning",
            "butuhFormulir": true,
            "dokumen": ["laporan-awal-junaedi.pdf"],
            "logStatus": [
                { "no": 1, "status": "LAPORAN AWAL DITERIMA", "keterangan": "-", "tanggal": "22-09-2023 10:00" },
                { "no": 2, "status": "REVISI DOKUMEN OLEH ASURANSI DI BNI", "keterangan": "Surat keterangan kematian tidak terbaca, mohon unggah ulang", "tanggal": "25-09-2023 13:30" }
            ]
        },
        {
            "id": 2,
            "klaimId": "CLM.00913",
            "debitur": "NINING MASRINI",
            "noPolis": "015920201121388500",
            "cabang": "KC KUNINGAN",
            "tanggalKematian": "12/02/2023",
            "nilaiKlaim": 65097611,
            "tanggalLapor": "20/02/2023",
            "deskripsi": "KLAIM MENINGGAL DUNIA",
            "status": "KLAIM SUDAH DIBAYAR (CLOSED FILE)",
            "statusType": "success",
            "butuhFormulir": false,
            "dokumen": ["laporan-awal-nining.pdf", "formulir-klaim-nining.pdf", "bukti-bayar-nining.pdf"],
            "logStatus": [
                { "no": 1, "status": "LAPORAN AWAL DITERIMA", "keterangan": "-", "tanggal": "20-02-2023 08:45" },
                { "no": 2, "status": "KLAIM SUDAH DIBAYAR (CLOSED FILE)", "keterangan": "Pembayaran selesai", "tanggal": "15-03-2023 10:20" }
            ]
        },
        {
            "id": 1,
            "klaimId": "CLM.00549",
            "debitur": "CECEP AHMAD",
            "noPolis": "015920201120020800",
            "cabang": "KC KUNINGAN",
            "tanggalKematian": "10/03/2022",
            "nilaiKlaim": 219229754,
            "tanggalLapor": "06/04/2022",
            "deskripsi": "SAKIT BIASA",
            "status": "KLAIM SUDAH DIBAYAR (CLOSED FILE)",
            "statusType": "success",
            "butuhFormulir": false,
            "dokumen": ["laporan-awal-cecep.pdf", "formulir-klaim-cecep.pdf", "bukti-bayar-cecep.pdf"],
            "logStatus": [
                { "no": 1, "status": "LAPORAN AWAL DITERIMA", "keterangan": "-", "tanggal": "06-04-2022 11:30" },
                { "no": 2, "status": "KLAIM SUDAH DIBAYAR (CLOSED FILE)", "keterangan": "Pembayaran selesai", "tanggal": "30-04-2022 15:00" }
            ]
        }
    ]
};
