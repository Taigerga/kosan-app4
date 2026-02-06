<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kos;

class FunctionController extends Controller
{
    public function fungsiSp()
    {
        $pemilikId = Auth::guard('pemilik')->user()->id_pemilik;
        
        // Ambil kos milik pemilik
        $kosList = Kos::where('id_pemilik', $pemilikId)
            ->where('status_kos', 'aktif')
            ->get();
        
        // Hitung menggunakan stored function untuk setiap kos
        $kosList->each(function ($kos) use ($pemilikId) {
            // Hitung persentase okupansi
            $okupansi = DB::selectOne("SELECT sf_persentase_okupansi(?) as persentase", [$kos->id_kos]);
            $kos->okupansi = $okupansi->persentase ?? 0;
            
            // Hitung rating
            $rating = DB::selectOne("SELECT sf_rating_kos(?) as rating", [$kos->id_kos]);
            $kos->rating = $rating->rating ?? 0;
        });

        // Hitung total pendapatan bulan ini menggunakan stored function
        $pendapatanBulanIni = DB::selectOne("SELECT sf_pendapatan_bulan_ini(?) as pendapatan", [$pemilikId]);
        
        // Hitung rata-rata durasi sewa menggunakan stored function
        $rataDurasiSewa = DB::selectOne("SELECT sf_rata_durasi_sewa(?) as rata_durasi", [$pemilikId]);

        // Summary
        $summary = [
            'pendapatan_bulan_ini' => 'Rp ' . number_format($pendapatanBulanIni->pendapatan ?? 0, 0, ',', '.'),
            'rata_durasi_sewa' => number_format($rataDurasiSewa->rata_durasi ?? 0, 1) . ' bulan',
            'total_kos' => $kosList->count(),
            'total_kamar' => $kosList->sum(function ($kos) {
                return $kos->kamar->count();
            })
        ];

        return view('pemilik.function.index', compact('kosList', 'summary', 'pemilikId'));
    }

    public function hitungOkupansi(Request $request)
    {
        $request->validate([
            'id_kos' => 'required|integer|exists:kos,id_kos'
        ]);

        // Cek kepemilikan
        $pemilikId = Auth::guard('pemilik')->user()->id_pemilik;
        $kos = Kos::where('id_kos', $request->id_kos)
            ->where('id_pemilik', $pemilikId)
            ->firstOrFail();

        $okupansi = DB::selectOne("SELECT sf_persentase_okupansi(?) as persentase", [$request->id_kos]);
        
        return response()->json([
            'success' => true,
            'okupansi' => $okupansi->persentase,
            'id_kos' => $request->id_kos,
            'nama_kos' => $kos->nama_kos
        ]);
    }

    public function hitungPendapatan(Request $request)
    {
        $pemilikId = Auth::guard('pemilik')->user()->id_pemilik;
        
        $pendapatan = DB::selectOne("SELECT sf_pendapatan_bulan_ini(?) as pendapatan", [$pemilikId]);
        
        return response()->json([
            'success' => true,
            'pendapatan' => 'Rp ' . number_format($pendapatan->pendapatan ?? 0, 0, ',', '.'),
            'bulan' => date('F Y')
        ]);
    }
}