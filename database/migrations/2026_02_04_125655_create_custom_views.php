<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. VIEW untuk Halaman Utama (Ringkasan Umum)
        DB::statement("
            CREATE OR REPLACE VIEW v_ringkasan_umum AS
            SELECT 
                COUNT(DISTINCT k.id_kos) AS total_kos_aktif,
                COUNT(DISTINCT CASE WHEN ka.status_kamar = 'tersedia' THEN ka.id_kamar END) AS total_kamar_tersedia,
                COUNT(DISTINCT p.id_pemilik) AS total_pemilik_aktif,
                COUNT(DISTINCT ph.id_penghuni) AS total_penghuni_aktif,
                COALESCE(SUM(CASE WHEN pb.status_pembayaran = 'lunas' THEN pb.jumlah END), 0) AS total_pendapatan_30hari,
                GROUP_CONCAT(DISTINCT k.kota ORDER BY k.kota SEPARATOR ', ') AS kota_terpopuler
            FROM kos k
            LEFT JOIN kamar ka ON k.id_kos = ka.id_kos
            LEFT JOIN pemilik p ON k.id_pemilik = p.id_pemilik AND p.status_pemilik = 'aktif'
            LEFT JOIN penghuni ph ON ph.status_penghuni = 'aktif'
            LEFT JOIN kontrak_sewa ks ON k.id_kos = ks.id_kos
            LEFT JOIN pembayaran pb ON ks.id_kontrak = pb.id_kontrak 
                AND pb.status_pembayaran = 'lunas' 
                AND pb.tanggal_bayar >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            WHERE k.status_kos = 'aktif'
        ");

        // 2. VIEW untuk Dashboard Pemilik (Detail per Kos)
        DB::statement("
            CREATE OR REPLACE VIEW v_dashboard_pemilik AS
            SELECT 
                k.id_kos,
                p.id_pemilik,
                k.nama_kos,
                k.alamat,
                k.kota,
                k.jenis_kos,
                COUNT(DISTINCT ka.id_kamar) AS total_kamar,
                COUNT(DISTINCT CASE WHEN ka.status_kamar = 'tersedia' THEN ka.id_kamar END) AS kamar_tersedia,
                COUNT(DISTINCT CASE WHEN ka.status_kamar = 'terisi' THEN ka.id_kamar END) AS kamar_terisi,
                COUNT(DISTINCT ks.id_kontrak) AS total_kontrak_aktif,
                COALESCE(SUM(CASE WHEN pb.status_pembayaran = 'lunas' AND MONTH(pb.tanggal_bayar) = MONTH(CURDATE()) 
                    THEN pb.jumlah END), 0) AS pendapatan_bulan_ini,
                COALESCE(AVG(r.rating), 0) AS rating_rata_rata,
                MAX(pb.tanggal_bayar) AS pembayaran_terakhir
            FROM kos k
            INNER JOIN pemilik p ON k.id_pemilik = p.id_pemilik
            LEFT JOIN kamar ka ON k.id_kos = ka.id_kos
            LEFT JOIN kontrak_sewa ks ON k.id_kos = ks.id_kos AND ks.status_kontrak = 'aktif'
            LEFT JOIN pembayaran pb ON ks.id_kontrak = pb.id_kontrak
            LEFT JOIN reviews r ON k.id_kos = r.id_kos
            WHERE k.status_kos = 'aktif'
            GROUP BY k.id_kos, p.id_pemilik, k.nama_kos, k.alamat, k.kota, k.jenis_kos
        ");

        // 3. VIEW untuk Dashboard Penghuni (Kontrak dan Pembayaran)
        DB::statement("
            CREATE OR REPLACE VIEW v_dashboard_penghuni AS
            SELECT 
                ph.id_penghuni,
                ph.nama AS nama_penghuni,
                k.nama_kos,
                ka.nomor_kamar,
                ks.tanggal_mulai,
                ks.tanggal_selesai,
                ks.status_kontrak,
                ks.harga_sewa,
                COUNT(DISTINCT pb.id_pembayaran) AS total_pembayaran,
                COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'lunas' THEN pb.id_pembayaran END) AS pembayaran_lunas,
                COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'belum' THEN pb.id_pembayaran END) AS pembayaran_belum,
                COUNT(DISTINCT CASE WHEN pb.status_pembayaran = 'terlambat' THEN pb.id_pembayaran END) AS pembayaran_terlambat,
                MAX(pb.tanggal_jatuh_tempo) AS jatuh_tempo_terdekat,
                GROUP_CONCAT(DISTINCT f.nama_fasilitas ORDER BY f.nama_fasilitas SEPARATOR ', ') AS fasilitas_kos
            FROM penghuni ph
            LEFT JOIN kontrak_sewa ks ON ph.id_penghuni = ks.id_penghuni
            LEFT JOIN kos k ON ks.id_kos = k.id_kos
            LEFT JOIN kamar ka ON ks.id_kamar = ka.id_kamar
            LEFT JOIN pembayaran pb ON ks.id_kontrak = pb.id_kontrak
            LEFT JOIN kos_fasilitas kf ON k.id_kos = kf.id_kos
            LEFT JOIN fasilitas f ON kf.id_fasilitas = f.id_fasilitas
            WHERE ph.status_penghuni = 'aktif'
            GROUP BY ph.id_penghuni, ph.nama, k.nama_kos, ka.nomor_kamar, ks.tanggal_mulai, 
                     ks.tanggal_selesai, ks.status_kontrak, ks.harga_sewa
        ");
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS v_ringkasan_umum');
        DB::statement('DROP VIEW IF EXISTS v_dashboard_pemilik');
        DB::statement('DROP VIEW IF EXISTS v_dashboard_penghuni');
    }
};