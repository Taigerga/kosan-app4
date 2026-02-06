<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ViewController extends Controller
{
    public function kontrakSaya()
    {
        $penghuniId = Auth::guard('penghuni')->user()->id_penghuni;
        
        $data = DB::table('v_dashboard_penghuni')
            ->where('id_penghuni', $penghuniId)
            ->orderBy('jatuh_tempo_terdekat', 'desc')
            ->get();

        // Format dan tambahkan informasi tambahan
        $data = $data->map(function ($item) {
            // Format tanggal
            $item->tanggal_mulai_formatted = Carbon::parse($item->tanggal_mulai)->format('d M Y');
            $item->tanggal_selesai_formatted = Carbon::parse($item->tanggal_selesai)->format('d M Y');
            
            // Hitung sisa hari kontrak
            $selesai = Carbon::parse($item->tanggal_selesai);
            $sekarang = Carbon::now();
            $item->sisa_hari = $sekarang->diffInDays($selesai, false);
            
            // Status warna berdasarkan sisa hari
            if ($item->sisa_hari < 0) {
                $item->status_warna = 'text-red-500';
                $item->status_text = 'Kadaluarsa';
            } elseif ($item->sisa_hari < 30) {
                $item->status_warna = 'text-yellow-500';
                $item->status_text = 'Segera berakhir';
            } else {
                $item->status_warna = 'text-green-500';
                $item->status_text = 'Aktif';
            }
            
            // Format harga
            $item->harga_sewa_formatted = 'Rp ' . number_format($item->harga_sewa, 0, ',', '.');
            
            return $item;
        });

        return view('penghuni.view.kontrak-saya', compact('data'));
    }
}