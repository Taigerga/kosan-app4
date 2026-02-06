<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Set default collation
        DB::statement("SET collation_connection = 'utf8mb4_unicode_ci'");

        // 1. STORED PROCEDURE untuk Public (Ringkasan Umum)
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_ringkasan_umum;
            CREATE PROCEDURE sp_ringkasan_umum()
            BEGIN
                SELECT 
                    COUNT(DISTINCT k.id_kos) AS total_kos_aktif,
                    COUNT(DISTINCT CASE WHEN ka.status_kamar = 'tersedia' THEN ka.id_kamar END) AS total_kamar_tersedia,
                    COUNT(DISTINCT p.id_pemilik) AS total_pemilik_aktif,
                    COUNT(DISTINCT ph.id_penghuni) AS total_penghuni_aktif,
                    COALESCE(SUM(CASE WHEN pb.status_pembayaran = 'lunas' THEN pb.jumlah END), 0) AS total_pendapatan_30hari,
                    GROUP_CONCAT(DISTINCT k.kota ORDER BY k.kota SEPARATOR ', ') AS kota_terpopuler,
                    COUNT(DISTINCT CASE WHEN r.id_review IS NOT NULL THEN r.id_review END) AS total_review,
                    ROUND(COALESCE(AVG(r.rating), 0), 1) AS rata_rata_rating,
                    COUNT(DISTINCT CASE WHEN k.jenis_kos = 'putra' THEN k.id_kos END) AS kos_putra,
                    COUNT(DISTINCT CASE WHEN k.jenis_kos = 'putri' THEN k.id_kos END) AS kos_putri,
                    COUNT(DISTINCT CASE WHEN k.jenis_kos = 'campuran' THEN k.id_kos END) AS kos_campuran
                FROM kos k
                LEFT JOIN kamar ka ON k.id_kos = ka.id_kos
                LEFT JOIN pemilik p ON k.id_pemilik = p.id_pemilik AND p.status_pemilik = 'aktif'
                LEFT JOIN penghuni ph ON ph.status_penghuni = 'aktif'
                LEFT JOIN kontrak_sewa ks ON k.id_kos = ks.id_kos
                LEFT JOIN pembayaran pb ON ks.id_kontrak = pb.id_kontrak 
                    AND pb.status_pembayaran = 'lunas' 
                    AND pb.tanggal_bayar >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                LEFT JOIN reviews r ON k.id_kos = r.id_kos
                WHERE k.status_kos = 'aktif';
            END
        ");

        // 2. STORED PROCEDURE untuk Pemilik (Analisis dengan Filter)
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_analisis_pemilik;
            CREATE PROCEDURE sp_analisis_pemilik(
                IN p_id_pemilik INT,
                IN p_tahun INT,
                IN p_bulan INT
            )
            BEGIN
                SELECT 
                    k.id_kos,
                    k.nama_kos,
                    k.alamat,
                    k.kota,
                    k.jenis_kos,
                    COUNT(DISTINCT ka.id_kamar) AS total_kamar,
                    COUNT(DISTINCT CASE WHEN ka.status_kamar = 'tersedia' THEN ka.id_kamar END) AS kamar_tersedia,
                    COUNT(DISTINCT CASE WHEN ka.status_kamar = 'terisi' THEN ka.id_kamar END) AS kamar_terisi,
                    COUNT(DISTINCT CASE WHEN ka.status_kamar = 'maintenance' THEN ka.id_kamar END) AS kamar_maintenance,
                    COUNT(DISTINCT ks.id_kontrak) AS total_kontrak,
                    COUNT(DISTINCT CASE WHEN ks.status_kontrak = 'aktif' THEN ks.id_kontrak END) AS kontrak_aktif,
                    COUNT(DISTINCT CASE WHEN ks.status_kontrak = 'pending' THEN ks.id_kontrak END) AS kontrak_pending,
                    COALESCE(SUM(CASE 
                        WHEN pb.status_pembayaran = 'lunas' 
                        AND (p_tahun IS NULL OR YEAR(pb.tanggal_bayar) = p_tahun)
                        AND (p_bulan IS NULL OR MONTH(pb.tanggal_bayar) = p_bulan)
                        THEN pb.jumlah 
                    END), 0) AS pendapatan_periode,
                    COALESCE(AVG(r.rating), 0) AS rating_rata_rata,
                    COUNT(DISTINCT r.id_review) AS jumlah_review,
                    MIN(ka.harga) AS harga_terendah,
                    MAX(ka.harga) AS harga_tertinggi,
                    CASE 
                        WHEN COUNT(DISTINCT ka.id_kamar) > 0 
                        THEN ROUND((COUNT(DISTINCT CASE WHEN ka.status_kamar = 'terisi' THEN ka.id_kamar END) / COUNT(DISTINCT ka.id_kamar)) * 100, 1)
                        ELSE 0 
                    END AS persentase_terisi,
                    MAX(pb.tanggal_bayar) AS pembayaran_terakhir
                FROM kos k
                INNER JOIN pemilik p ON k.id_pemilik = p.id_pemilik
                LEFT JOIN kamar ka ON k.id_kos = ka.id_kos
                LEFT JOIN kontrak_sewa ks ON k.id_kos = ks.id_kos
                LEFT JOIN pembayaran pb ON ks.id_kontrak = pb.id_kontrak
                LEFT JOIN reviews r ON k.id_kos = r.id_kos
                WHERE k.status_kos = 'aktif'
                    AND p.id_pemilik = p_id_pemilik
                GROUP BY k.id_kos, k.nama_kos, k.alamat, k.kota, k.jenis_kos
                ORDER BY k.nama_kos;
            END
        ");

        // 3. STORED PROCEDURE untuk Penghuni (Detail Kontrak & Pembayaran)
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_detail_penghuni;
            CREATE PROCEDURE sp_detail_penghuni(
                IN p_id_penghuni INT,
                IN p_status_kontrak VARCHAR(20)
            )
            BEGIN
                SELECT 
                    ph.id_penghuni,
                    ph.nama AS nama_penghuni,
                    ph.email AS email_penghuni,
                    ph.no_hp AS no_hp_penghuni,
                    k.nama_kos,
                    k.alamat AS alamat_kos,
                    k.kota AS kota_kos,
                    k.provinsi AS provinsi_kos,
                    ka.nomor_kamar,
                    ka.tipe_kamar,
                    ka.luas_kamar,
                    ka.harga AS harga_kamar,
                    ks.id_kontrak,
                    ks.tanggal_daftar,
                    ks.tanggal_mulai,
                    ks.tanggal_selesai,
                    ks.durasi_sewa,
                    ks.harga_sewa,
                    ks.status_kontrak,
                    DATEDIFF(ks.tanggal_selesai, CURDATE()) AS sisa_hari,
                    COUNT(DISTINCT pb.id_pembayaran) AS total_transaksi,
                    COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'lunas' THEN pb.id_pembayaran END) AS transaksi_lunas,
                    COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'belum' THEN pb.id_pembayaran END) AS transaksi_belum,
                    COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'terlambat' THEN pb.id_pembayaran END) AS transaksi_terlambat,
                    COALESCE(SUM(CASE WHEN pb.status_pembayaran = 'lunas' THEN pb.jumlah END), 0) AS total_dibayar,
                    COALESCE(SUM(CASE WHEN pb.status_pembayaran IN ('belum', 'terlambat') THEN pb.jumlah END), 0) AS total_tunggakan,
                    MAX(CASE WHEN pb.status_pembayaran IN ('belum', 'terlambat') THEN pb.tanggal_jatuh_tempo END) AS jatuh_tempo_terdekat,
                    GROUP_CONCAT(DISTINCT f.nama_fasilitas ORDER BY f.nama_fasilitas SEPARATOR ', ') AS fasilitas_kos,
                    COALESCE(AVG(r.rating), 0) AS rating_kos,
                    COUNT(DISTINCT r.id_review) AS review_count
                FROM penghuni ph
                LEFT JOIN kontrak_sewa ks ON ph.id_penghuni = ks.id_penghuni
                LEFT JOIN kos k ON ks.id_kos = k.id_kos
                LEFT JOIN kamar ka ON ks.id_kamar = ka.id_kamar
                LEFT JOIN pembayaran pb ON ks.id_kontrak = pb.id_kontrak
                LEFT JOIN kos_fasilitas kf ON k.id_kos = kf.id_kos
                LEFT JOIN fasilitas f ON kf.id_fasilitas = f.id_fasilitas
                LEFT JOIN reviews r ON k.id_kos = r.id_kos AND r.id_penghuni = ph.id_penghuni
                WHERE ph.id_penghuni = p_id_penghuni
                    AND (p_status_kontrak IS NULL OR ks.status_kontrak COLLATE utf8mb4_unicode_ci = p_status_kontrak COLLATE utf8mb4_unicode_ci)
                GROUP BY ph.id_penghuni, ph.nama, ph.email, ph.no_hp, 
                         k.nama_kos, k.alamat, k.kota, k.provinsi,
                         ka.nomor_kamar, ka.tipe_kamar, ka.luas_kamar, ka.harga,
                         ks.id_kontrak, ks.tanggal_daftar, ks.tanggal_mulai, 
                         ks.tanggal_selesai, ks.durasi_sewa, ks.harga_sewa, ks.status_kontrak
                ORDER BY ks.tanggal_mulai DESC, ks.status_kontrak;
            END
        ");

        // 4. STORED PROCEDURE untuk Laporan Bulanan Pemilik
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_laporan_bulanan_pemilik;
            CREATE PROCEDURE sp_laporan_bulanan_pemilik(
                IN p_id_pemilik INT,
                IN p_tahun INT,
                IN p_bulan INT
            )
            BEGIN
                SELECT 
                    k.nama_kos,
                    MONTH(pb.tanggal_bayar) AS bulan,
                    YEAR(pb.tanggal_bayar) AS tahun,
                    COUNT(DISTINCT pb.id_pembayaran) AS jumlah_transaksi,
                    SUM(pb.jumlah) AS total_pendapatan,
                    COUNT(DISTINCT ph.id_penghuni) AS jumlah_penghuni,
                    COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'lunas' THEN pb.id_pembayaran END) AS transaksi_lunas,
                    COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'terlambat' THEN pb.id_pembayaran END) AS transaksi_terlambat,
                    COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'belum' THEN pb.id_pembayaran END) AS transaksi_belum,
                    COALESCE(SUM(CASE WHEN pb.status_pembayaran = 'terlambat' THEN pb.jumlah END), 0) AS denda_terlambat,
                    COALESCE(AVG(DATEDIFF(pb.tanggal_bayar, pb.tanggal_jatuh_tempo)), 0) AS rata_keterlambatan
                FROM kos k
                INNER JOIN pemilik p ON k.id_pemilik = p.id_pemilik
                LEFT JOIN kontrak_sewa ks ON k.id_kos = ks.id_kos
                LEFT JOIN pembayaran pb ON ks.id_kontrak = pb.id_kontrak
                LEFT JOIN penghuni ph ON ks.id_penghuni = ph.id_penghuni
                WHERE p.id_pemilik = p_id_pemilik
                    AND (p_tahun IS NULL OR YEAR(pb.tanggal_bayar) = p_tahun)
                    AND (p_bulan IS NULL OR MONTH(pb.tanggal_bayar) = p_bulan)
                GROUP BY k.nama_kos, MONTH(pb.tanggal_bayar), YEAR(pb.tanggal_bayar)
                ORDER BY tahun DESC, bulan DESC, total_pendapatan DESC;
            END
        ");
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_ringkasan_umum');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_analisis_pemilik');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_detail_penghuni');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_laporan_bulanan_pemilik');
    }
};