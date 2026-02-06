<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcedureController extends Controller
{
    public function analisisSp(Request $request)
    {
        $pemilikId = Auth::guard('pemilik')->user()->id_pemilik;
        
        // Ambil parameter filter
        $tahun = $request->input('tahun', null);
        $bulan = $request->input('bulan', null);

        // Panggil stored procedure dengan parameter
        $data = DB::select('CALL sp_analisis_pemilik(?, ?, ?)', [
            $pemilikId,
            $tahun,
            $bulan
        ]);

        // Format data
        $data = collect($data)->map(function ($item) {
            $item->pendapatan_periode = 'Rp ' . number_format($item->pendapatan_periode, 0, ',', '.');
            $item->harga_terendah = 'Rp ' . number_format($item->harga_terendah, 0, ',', '.');
            $item->harga_tertinggi = 'Rp ' . number_format($item->harga_tertinggi, 0, ',', '.');
            $item->rating_rata_rata = number_format($item->rating_rata_rata, 1);
            $item->persentase_terisi = number_format($item->persentase_terisi, 1) . '%';
            $item->pembayaran_terakhir = $item->pembayaran_terakhir 
                ? \Carbon\Carbon::parse($item->pembayaran_terakhir)->format('d M Y') 
                : 'Belum ada';
            return $item;
        });

        // Hitung totals
        $totalPendapatan = collect($data)->sum(function ($item) {
            return str_replace(['Rp ', '.'], '', $item->pendapatan_periode);
        });
        $totalPendapatan = 'Rp ' . number_format($totalPendapatan, 0, ',', '.');

        // Data untuk filter dropdown
        $tahunList = DB::table('pembayaran')
            ->select(DB::raw('YEAR(tanggal_bayar) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('pemilik.procedure.analisis-sp', compact('data', 'totalPendapatan', 'tahunList', 'tahun', 'bulan'));
    }

    public function laporanBulanan(Request $request)
    {
        $pemilikId = Auth::guard('pemilik')->user()->id_pemilik;
        
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', null);

        // Panggil stored procedure laporan bulanan
        $laporan = DB::select('CALL sp_laporan_bulanan_pemilik(?, ?, ?)', [
            $pemilikId,
            $tahun,
            $bulan
        ]);

        // Format data
        $laporan = collect($laporan)->map(function ($item) {
            $item->total_pendapatan = 'Rp ' . number_format($item->total_pendapatan, 0, ',', '.');
            $item->denda_terlambat = 'Rp ' . number_format($item->denda_terlambat, 0, ',', '.');
            $item->rata_keterlambatan = number_format($item->rata_keterlambatan, 1) . ' hari';
            
            // Tambahkan nama bulan
            if ($item->bulan) {
                $item->nama_bulan = \Carbon\Carbon::create()->month($item->bulan)->locale('id')->monthName;
            }
            
            return $item;
        });

        // Hitung totals
        $summary = [
            'total_pendapatan' => 'Rp ' . number_format(collect($laporan)->sum(function ($item) {
                return str_replace(['Rp ', '.'], '', $item->total_pendapatan);
            }), 0, ',', '.'),
            'total_transaksi' => collect($laporan)->sum('jumlah_transaksi'),
            'total_penghuni' => collect($laporan)->sum('jumlah_penghuni'),
            'total_denda' => 'Rp ' . number_format(collect($laporan)->sum(function ($item) {
                return str_replace(['Rp ', '.'], '', $item->denda_terlambat);
            }), 0, ',', '.'),
        ];

        $tahunList = DB::table('pembayaran')
            ->select(DB::raw('YEAR(tanggal_bayar) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('pemilik.procedure.laporan-bulanan', compact('laporan', 'summary', 'tahunList', 'tahun', 'bulan'));
    }
}