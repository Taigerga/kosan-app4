<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $pdo = DB::connection()->getPdo();
        
        $pdo->exec("DROP FUNCTION IF EXISTS sf_rating_kos");
        $pdo->exec("
            CREATE FUNCTION sf_rating_kos(p_id_kos INT)
            RETURNS DECIMAL(3,1)
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_rating DECIMAL(3,1);
                SELECT COALESCE(AVG(rating), 0.0) INTO v_rating
                FROM reviews
                WHERE id_kos = p_id_kos;
                RETURN v_rating;
            END
        ");
        
        $pdo->exec("DROP FUNCTION IF EXISTS sf_kamar_tersedia_kota");
        $pdo->exec("
            CREATE FUNCTION sf_kamar_tersedia_kota(p_kota VARCHAR(100))
            RETURNS INT
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_jumlah INT;
                SELECT COUNT(*) INTO v_jumlah
                FROM kamar ka
                INNER JOIN kos k ON ka.id_kos = k.id_kos
                WHERE k.kota = p_kota COLLATE utf8mb4_unicode_ci
                    AND ka.status_kamar = 'tersedia'
                    AND k.status_kos = 'aktif';
                RETURN v_jumlah;
            END
        ");
        
        $pdo->exec("DROP FUNCTION IF EXISTS sf_pendapatan_bulan_ini");
        $pdo->exec("
            CREATE FUNCTION sf_pendapatan_bulan_ini(p_id_pemilik INT)
            RETURNS DECIMAL(12,2)
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_pendapatan DECIMAL(12,2);
                SELECT COALESCE(SUM(pb.jumlah), 0) INTO v_pendapatan
                FROM pembayaran pb
                INNER JOIN kontrak_sewa ks ON pb.id_kontrak = ks.id_kontrak
                INNER JOIN kos k ON ks.id_kos = k.id_kos
                WHERE k.id_pemilik = p_id_pemilik
                    AND pb.status_pembayaran = 'lunas'
                    AND MONTH(pb.tanggal_bayar) = MONTH(CURDATE())
                    AND YEAR(pb.tanggal_bayar) = YEAR(CURDATE());
                RETURN v_pendapatan;
            END
        ");
        
        $pdo->exec("DROP FUNCTION IF EXISTS sf_persentase_okupansi");
        $pdo->exec("
            CREATE FUNCTION sf_persentase_okupansi(p_id_kos INT)
            RETURNS DECIMAL(5,2)
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_total_kamar INT;
                DECLARE v_kamar_terisi INT;
                DECLARE v_persentase DECIMAL(5,2);
                SELECT COUNT(*) INTO v_total_kamar FROM kamar WHERE id_kos = p_id_kos;
                SELECT COUNT(*) INTO v_kamar_terisi FROM kamar WHERE id_kos = p_id_kos AND status_kamar = 'terisi';
                IF v_total_kamar > 0 THEN
                    SET v_persentase = (v_kamar_terisi / v_total_kamar) * 100;
                ELSE
                    SET v_persentase = 0;
                END IF;
                RETURN v_persentase;
            END
        ");
        
        $pdo->exec("DROP FUNCTION IF EXISTS sf_total_pembayaran_penghuni");
        $pdo->exec("
            CREATE FUNCTION sf_total_pembayaran_penghuni(p_id_penghuni INT)
            RETURNS DECIMAL(12,2)
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_total DECIMAL(12,2);
                SELECT COALESCE(SUM(jumlah), 0) INTO v_total
                FROM pembayaran
                WHERE id_penghuni = p_id_penghuni
                    AND status_pembayaran = 'lunas';
                RETURN v_total;
            END
        ");
        
        $pdo->exec("DROP FUNCTION IF EXISTS sf_sisa_hari_kontrak");
        $pdo->exec("
            CREATE FUNCTION sf_sisa_hari_kontrak(p_id_kontrak INT)
            RETURNS INT
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_sisa_hari INT;
                DECLARE v_tanggal_selesai DATE;
                SELECT tanggal_selesai INTO v_tanggal_selesai
                FROM kontrak_sewa
                WHERE id_kontrak = p_id_kontrak;
                IF v_tanggal_selesai IS NOT NULL THEN
                    SET v_sisa_hari = DATEDIFF(v_tanggal_selesai, CURDATE());
                ELSE
                    SET v_sisa_hari = NULL;
                END IF;
                RETURN v_sisa_hari;
            END
        ");
        
        $pdo->exec("DROP FUNCTION IF EXISTS sf_cek_kamar_tersedia");
        $pdo->exec("
            CREATE FUNCTION sf_cek_kamar_tersedia(p_id_kos INT)
            RETURNS VARCHAR(50)
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_jumlah_tersedia INT;
                DECLARE v_status VARCHAR(50);
                SELECT COUNT(*) INTO v_jumlah_tersedia
                FROM kamar
                WHERE id_kos = p_id_kos AND status_kamar = 'tersedia';
                IF v_jumlah_tersedia = 0 THEN
                    SET v_status = 'Penuh';
                ELSEIF v_jumlah_tersedia <= 3 THEN
                    SET v_status = 'Hampir Penuh';
                ELSE
                    SET v_status = 'Masih Tersedia';
                END IF;
                RETURN v_status;
            END
        ");
        
        $pdo->exec("DROP FUNCTION IF EXISTS sf_rata_durasi_sewa");
        $pdo->exec("
            CREATE FUNCTION sf_rata_durasi_sewa(p_id_pemilik INT)
            RETURNS DECIMAL(5,2)
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_rata_durasi DECIMAL(5,2);
                SELECT COALESCE(AVG(durasi_sewa), 0) INTO v_rata_durasi
                FROM kontrak_sewa ks
                INNER JOIN kos k ON ks.id_kos = k.id_kos
                WHERE k.id_pemilik = p_id_pemilik
                    AND ks.status_kontrak = 'aktif';
                RETURN v_rata_durasi;
            END
        ");
    }

    public function down()
    {
        DB::statement('DROP FUNCTION IF EXISTS sf_rating_kos');
        DB::statement('DROP FUNCTION IF EXISTS sf_kamar_tersedia_kota');
        DB::statement('DROP FUNCTION IF EXISTS sf_pendapatan_bulan_ini');
        DB::statement('DROP FUNCTION IF EXISTS sf_persentase_okupansi');
        DB::statement('DROP FUNCTION IF EXISTS sf_total_pembayaran_penghuni');
        DB::statement('DROP FUNCTION IF EXISTS sf_sisa_hari_kontrak');
        DB::statement('DROP FUNCTION IF EXISTS sf_cek_kamar_tersedia');
        DB::statement('DROP FUNCTION IF EXISTS sf_rata_durasi_sewa');
    }
};