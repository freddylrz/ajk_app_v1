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

export const ClientData = {

    /* ── Info user login ─────────────────────────────────── */
    "user": {
        "username": "OPR",
        "kantor": "KC KUNINGAN",
        "cabang": "BNI Cabang KC KUNINGAN",
        "role": "client"
    },

    /* ── Ringkasan dashboard ─────────────────────────────── */
    "dashboard": {
        "totalPertanggungan": 100000000,
        "totalPremi": 1000000,
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
    /* Kategori Debitur mengikuti master.tb_debtor_category (data asli). */
    "master": {
        "jenisKelamin": ["Laki-laki", "Perempuan"],
        "kategoriDebitur": ["Pegawai Swasta", "ASN", "BUMN", "BUMD", "TNI", "POLRI", "Lainnya"],

        /* Kuesioner Keterangan Kesehatan (SPK). "trigger" = jawaban yang
           mewajibkan kolom Keterangan diisi. khususWanita menandai
           pertanyaan yang hanya berlaku untuk debitur perempuan. */
        "kesehatanQuestions": [
            { "no": 1, "pertanyaan": "Apakah Anda sekarang dalam keadaan sehat? Jika \"Tidak\", jelaskan!", "trigger": "TIDAK" },
            { "no": 2, "pertanyaan": "Apakah dalam 5 tahun terakhir Anda pernah dioperasi/dirawat di Rumah Sakit atau dalam masa pengobatan/perawatan yang membutuhkan obat-obatan dalam masa lama? Jika \"Ya\", jelaskan!", "trigger": "YA" },
            { "no": 3, "pertanyaan": "Apakah berat badan Anda berubah dalam 12 bulan terakhir ini? Jika \"Ya\", jelaskan!", "trigger": "YA" },
            { "no": 4, "pertanyaan": "Apakah Anda pernah atau sedang menderita penyakit: cacat, tumor/kanker, TBC, asma, kencing manis, hati, ginjal, stroke, tekanan darah tinggi, gangguan jiwa atau penyakit lainnya? Jika \"Ya\", jelaskan!", "trigger": "YA" },
            { "no": 5, "pertanyaan": "Khusus untuk wanita: Apakah Anda sedang hamil? Jika \"Ya\", berapa minggu usia kandungan?", "trigger": "YA", "khususWanita": true }
        ]
    },

    /* ── Data penutupan Reguler Griya (list data) ─────────── */
    "penutupan": [
        {
            "id": 1,
            "kantor": "KC KUNINGAN",
            "debitur": "BUDI SANTOSO",
            "kategoriDebitur": "ASN",
            "namaInstansi": "Pemerintah Kabupaten Indramayu",
            "tanggalLahir": "12/05/1985",
            "umur": "41 Tahun",
            "pangkatJabatan": "Penata Muda / III-a",
            "noKtp": "3212121205850001",
            "jenisKelamin": "Laki-laki",
            "noHp": "081234567890",
            "email": "budi.santoso@gmail.com",
            "alamatKtp": "JL. SUDIRMAN NO. 10 RT 001 RW 002 KEL. LEMAHMEKAR KEC. INDRAMAYU KAB. INDRAMAYU",
            "alamatDomisili": "PERUM GRIYA ASRI BLOK C NO. 5 KEC. INDRAMAYU KAB. INDRAMAYU",
            "noRek": "0",
            "noPk": "045/IDM/PK-GRIYA/2024",
            "tenor": 180,
            "periode": "15/03/2024 - 15/03/2039",
            "plafondKredit": 350000000,
            "ratePremi": 6.2,
            "nilaiPremi": 21700000,
            "status": "MENUNGGU VALIDASI SPV",
            "statusType": "info",
            "inputDate": "15-03-2024 10:20",
            "noPolis": "-",
            "kesehatan": [
                { "no": 1, "jawaban": "YA", "keterangan": "-" },
                { "no": 2, "jawaban": "TIDAK", "keterangan": "-" },
                { "no": 3, "jawaban": "TIDAK", "keterangan": "-" },
                { "no": 4, "jawaban": "TIDAK", "keterangan": "-" },
                { "no": 5, "jawaban": "-", "keterangan": "-" }
            ],
            "files": ["ktp-budi.jpeg", "pk-depan-budi.jpeg", "pk-samping-budi.jpeg", "spk-budi.pdf"],
            "logStatus": [
                { "no": 1, "status": "MENUNGGU VALIDASI SPV", "keterangan": "-", "tanggal": "15-03-2024 10:20" }
            ]
        },
        {
            "id": 2,
            "kantor": "KC KUNINGAN",
            "debitur": "RATNA DEWI KUSUMA",
            "kategoriDebitur": "Pegawai Swasta",
            "namaInstansi": "PT Maju Bersama Sejahtera",
            "tanggalLahir": "22/08/1990",
            "umur": "35 Tahun",
            "pangkatJabatan": "Staff Keuangan",
            "noKtp": "3212126208900002",
            "jenisKelamin": "Perempuan",
            "noHp": "082198765432",
            "email": "ratna.dewi@example.com",
            "alamatKtp": "JL. MERDEKA NO. 25 RT 002 RW 001 KEL. KARANGANYAR KEC. INDRAMAYU KAB. INDRAMAYU",
            "alamatDomisili": "JL. MERDEKA NO. 25 RT 002 RW 001 KEL. KARANGANYAR KEC. INDRAMAYU KAB. INDRAMAYU",
            "noRek": "9876543210",
            "noPk": "078/IDM/PK-GRIYA/2024",
            "tenor": 120,
            "periode": "02/05/2024 - 02/05/2034",
            "plafondKredit": 275000000,
            "ratePremi": 5.8,
            "nilaiPremi": 15950000,
            "status": "REVISI INPUT DATA DEBITUR",
            "statusType": "warning",
            "inputDate": "02-05-2024 14:05",
            "noPolis": "-",
            "kesehatan": [
                { "no": 1, "jawaban": "YA", "keterangan": "-" },
                { "no": 2, "jawaban": "TIDAK", "keterangan": "-" },
                { "no": 3, "jawaban": "YA", "keterangan": "Naik 4 kg dalam 6 bulan terakhir" },
                { "no": 4, "jawaban": "TIDAK", "keterangan": "-" },
                { "no": 5, "jawaban": "TIDAK", "keterangan": "-" }
            ],
            "files": ["ktp-ratna.jpeg", "pk-depan-ratna.jpeg", "pk-samping-ratna.jpeg", "spk-ratna.pdf"],
            "logStatus": [
                { "no": 1, "status": "MENUNGGU VALIDASI SPV", "keterangan": "-", "tanggal": "02-05-2024 14:05" },
                { "no": 2, "status": "REVISI INPUT DATA DEBITUR", "keterangan": "Surat Pernyataan Kesehatan belum ditandatangani", "tanggal": "03-05-2024 09:15" }
            ]
        },
        {
            "id": 3,
            "kantor": "KC KUNINGAN",
            "debitur": "AGUS PRASETYO",
            "kategoriDebitur": "BUMN",
            "namaInstansi": "PT Bank Negara Indonesia (Persero) Tbk",
            "tanggalLahir": "03/11/1980",
            "umur": "45 Tahun",
            "pangkatJabatan": "Kepala Unit",
            "noKtp": "3212120311800003",
            "jenisKelamin": "Laki-laki",
            "noHp": "085711223344",
            "email": "agus.prasetyo@bni.co.id",
            "alamatKtp": "JL. PAHLAWAN NO. 8 RT 003 RW 002 KEL. PAOMAN KEC. INDRAMAYU KAB. INDRAMAYU",
            "alamatDomisili": "JL. PAHLAWAN NO. 8 RT 003 RW 002 KEL. PAOMAN KEC. INDRAMAYU KAB. INDRAMAYU",
            "noRek": "5566778899",
            "noPk": "012/IDM/PK-GRIYA/2023",
            "tenor": 180,
            "periode": "10/01/2023 - 10/01/2038",
            "plafondKredit": 520000000,
            "ratePremi": 6.5,
            "nilaiPremi": 33800000,
            "status": "POLIS TERBIT",
            "statusType": "success",
            "inputDate": "10-01-2023 09:00",
            "noPolis": "015920230110090011",
            "kesehatan": [
                { "no": 1, "jawaban": "YA", "keterangan": "-" },
                { "no": 2, "jawaban": "TIDAK", "keterangan": "-" },
                { "no": 3, "jawaban": "TIDAK", "keterangan": "-" },
                { "no": 4, "jawaban": "TIDAK", "keterangan": "-" },
                { "no": 5, "jawaban": "-", "keterangan": "-" }
            ],
            "files": ["ktp-agus.jpeg", "pk-depan-agus.jpeg", "pk-samping-agus.jpeg", "spk-agus.pdf"],
            "logStatus": [
                { "no": 1, "status": "MENUNGGU VALIDASI SPV", "keterangan": "-", "tanggal": "10-01-2023 09:00" },
                { "no": 2, "status": "DIVALIDASI SPV", "keterangan": "Data lengkap", "tanggal": "11-01-2023 08:30" },
                { "no": 3, "status": "POLIS TERBIT", "keterangan": "No. Polis 015920230110090011", "tanggal": "13-01-2023 15:10" }
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
