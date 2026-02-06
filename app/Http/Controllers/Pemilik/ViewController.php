<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{
    public function kosAnalisis()
    {
        $pemilikId = Auth::guard('pemilik')->user()->id_pemilik;
        
        $data = DB::table('v_dashboard_pemilik')
            ->where('id_pemilik', $pemilikId)
            ->orderBy('nama_kos')
            ->get();

        // Format angka untuk tampilan
        $data = $data->map(function ($item) {
            $item->pendapatan_bulan_ini = 'Rp ' . number_format($item->pendapatan_bulan_ini, 0, ',', '.');
            $item->rating_rata_rata = number_format($item->rating_rata_rata, 1);
            $item->pembayaran_terakhir = $item->pembayaran_terakhir 
                ? \Carbon\Carbon::parse($item->pembayaran_terakhir)->format('d M Y') 
                : 'Belum ada';
            return $item;
        });

        $totalPendapatan = DB::table('v_dashboard_pemilik')
            ->where('id_pemilik', $pemilikId)
            ->sum('pendapatan_bulan_ini');

        $totalPendapatan = 'Rp ' . number_format($totalPendapatan, 0, ',', '.');

        return view('pemilik.view.kos-analisis', compact('data', 'totalPendapatan'));
    }
}