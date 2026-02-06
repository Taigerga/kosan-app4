<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\KontrakSewa;

class FunctionController extends Controller
{
    public function fungsiSp()
    {
        $penghuniId = Auth::guard('penghuni')->user()->id_penghuni;
        
        // Ambil kontrak aktif penghuni
        $kontrakList = KontrakSewa::with(['kos', 'kamar'])
            ->where('id_penghuni', $penghuniId)
            ->where('status_kontrak', 'aktif')
            ->get();
        
        // Hitung menggunakan stored function untuk setiap kontrak
        $kontrakList->each(function ($kontrak) {
            // Hitung sisa hari kontrak
            $sisaHari = DB::selectOne("SELECT sf_sisa_hari_kontrak(?) as sisa_hari", [$kontrak->id_kontrak]);
            $kontrak->sisa_hari = $sisaHari->sisa_hari ?? null;
            
            // Tentukan status warna
            if ($kontrak->sisa_hari === null) {
                $kontrak->status_warna = 'gray';
                $kontrak->status_text = 'Tidak diketahui';
            } elseif ($kontrak->sisa_hari < 0) {
                $kontrak->status_warna = 'red';
                $kontrak->status_text = 'Kadaluarsa';
            } elseif ($kontrak->sisa_hari < 7) {
                $kontrak->status_warna = 'yellow';
                $kontrak->status_text = 'Segera berakhir';
            } else {
                $kontrak->status_warna = 'green';
                $kontrak->status_text = 'Aktif';
            }
        });

        // Hitung total pembayaran menggunakan stored function
        $totalPembayaran = DB::selectOne("SELECT sf_total_pembayaran_penghuni(?) as total", [$penghuniId]);

        // Hitung kontrak yang akan segera berakhir (kurang dari 30 hari)
        $kontrakMendatang = $kontrakList->filter(function ($kontrak) {
            return $kontrak->sisa_hari !== null && $kontrak->sisa_hari > 0 && $kontrak->sisa_hari < 30;
        });

        // Summary
        $summary = [
            'total_pembayaran' => 'Rp ' . number_format($totalPembayaran->total ?? 0, 0, ',', '.'),
            'total_kontrak' => $kontrakList->count(),
            'kontrak_aktif' => $kontrakList->count(),
            'kontrak_mendatang' => $kontrakMendatang->count(),
            'rata_sisa_hari' => $kontrakList->avg('sisa_hari') ? number_format($kontrakList->avg('sisa_hari'), 0) : 0
        ];

        return view('penghuni.function.index', compact('kontrakList', 'summary', 'penghuniId'));
    }

    public function hitungSisaHari(Request $request)
    {
        $request->validate([
            'id_kontrak' => 'required|integer|exists:kontrak_sewa,id_kontrak'
        ]);

        // Cek kepemilikan kontrak
        $penghuniId = Auth::guard('penghuni')->user()->id_penghuni;
        $kontrak = KontrakSewa::where('id_kontrak', $request->id_kontrak)
            ->where('id_penghuni', $penghuniId)
            ->firstOrFail();

        $sisaHari = DB::selectOne("SELECT sf_sisa_hari_kontrak(?) as sisa_hari", [$request->id_kontrak]);
        
        // Format response
        $status = 'Tidak diketahui';
        if ($sisaHari->sisa_hari !== null) {
            if ($sisaHari->sisa_hari < 0) {
                $status = 'Kadaluarsa (' . abs($sisaHari->sisa_hari) . ' hari lalu)';
            } else {
                $status = $sisaHari->sisa_hari . ' hari lagi';
            }
        }
        
        return response()->json([
            'success' => true,
            'sisa_hari' => $sisaHari->sisa_hari,
            'status' => $status,
            'id_kontrak' => $request->id_kontrak
        ]);
    }

    public function hitungTotalPembayaran()
    {
        $penghuniId = Auth::guard('penghuni')->user()->id_penghuni;
        
        $total = DB::selectOne("SELECT sf_total_pembayaran_penghuni(?) as total", [$penghuniId]);
        
        return response()->json([
            'success' => true,
            'total_pembayaran' => 'Rp ' . number_format($total->total ?? 0, 0, ',', '.'),
            'id_penghuni' => $penghuniId
        ]);
    }
}