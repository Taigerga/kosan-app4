<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcedureController extends Controller
{
    public function ringkasanSp()
    {
        // Panggil stored procedure tanpa parameter
        $ringkasan = DB::select('CALL sp_ringkasan_umum()');
        $ringkasan = $ringkasan[0] ?? null;

        // Format data untuk tampilan
        if ($ringkasan) {
            $ringkasan->total_kos_aktif = number_format($ringkasan->total_kos_aktif, 0, ',', '.');
            $ringkasan->total_kamar_tersedia = number_format($ringkasan->total_kamar_tersedia, 0, ',', '.');
            $ringkasan->total_pemilik_aktif = number_format($ringkasan->total_pemilik_aktif, 0, ',', '.');
            $ringkasan->total_penghuni_aktif = number_format($ringkasan->total_penghuni_aktif, 0, ',', '.');
            $ringkasan->total_pendapatan_30hari = 'Rp ' . number_format($ringkasan->total_pendapatan_30hari, 0, ',', '.');
            $ringkasan->total_review = number_format($ringkasan->total_review, 0, ',', '.');
            $ringkasan->kos_putra = number_format($ringkasan->kos_putra, 0, ',', '.');
            $ringkasan->kos_putri = number_format($ringkasan->kos_putri, 0, ',', '.');
            $ringkasan->kos_campuran = number_format($ringkasan->kos_campuran, 0, ',', '.');
        }

        return view('public.procedure.ringkasan-sp', compact('ringkasan'));
    }
}