<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{
    public function ringkasan()
    {
        $ringkasan = DB::table('v_ringkasan_umum')->first();
        
        // Format angka dengan separator
        if ($ringkasan) {
            $ringkasan->total_kos_aktif = number_format($ringkasan->total_kos_aktif, 0, ',', '.');
            $ringkasan->total_kamar_tersedia = number_format($ringkasan->total_kamar_tersedia, 0, ',', '.');
            $ringkasan->total_pemilik_aktif = number_format($ringkasan->total_pemilik_aktif, 0, ',', '.');
            $ringkasan->total_penghuni_aktif = number_format($ringkasan->total_penghuni_aktif, 0, ',', '.');
            $ringkasan->total_pendapatan_30hari = 'Rp ' . number_format($ringkasan->total_pendapatan_30hari, 0, ',', '.');
        }

        return view('public.view.ringkasan', compact('ringkasan'));
    }
}