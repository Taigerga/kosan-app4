<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kos;

class FunctionController extends Controller
{
    public function fungsiSp()
    {
        // Ambil beberapa kos untuk demo
        $kosList = Kos::where('status_kos', 'aktif')
            ->limit(5)
            ->get();
        
        // Panggil stored function untuk setiap kos
        $kosList->each(function ($kos) {
            // Hitung rating menggunakan stored function
            $rating = DB::selectOne("SELECT sf_rating_kos(?) as rating", [$kos->id_kos]);
            $kos->rating = $rating->rating ?? 0;
            
            // Cek ketersediaan kamar menggunakan stored function
            $status = DB::selectOne("SELECT sf_cek_kamar_tersedia(?) as status", [$kos->id_kos]);
            $kos->status_kamar = $status->status ?? 'Tidak diketahui';
        });

        // Hitung kamar tersedia di beberapa kota menggunakan stored function
        $kotaKamar = [
            'Kota Bandung' => DB::selectOne("SELECT sf_kamar_tersedia_kota(?) as jumlah", ['Kota Bandung'])->jumlah ?? 0,
            'Bogor' => DB::selectOne("SELECT sf_kamar_tersedia_kota(?) as jumlah", ['Bogor'])->jumlah ?? 0,
            'Jakarta Selatan' => DB::selectOne("SELECT sf_kamar_tersedia_kota(?) as jumlah", ['Jakarta Selatan'])->jumlah ?? 0,
        ];

        return view('public.function.index', compact('kosList', 'kotaKamar'));
    }

    public function cekRating(Request $request)
    {
        $request->validate([
            'id_kos' => 'required|integer|exists:kos,id_kos'
        ]);

        $rating = DB::selectOne("SELECT sf_rating_kos(?) as rating", [$request->id_kos]);
        
        return response()->json([
            'success' => true,
            'rating' => $rating->rating,
            'id_kos' => $request->id_kos
        ]);
    }

    public function cekKota(Request $request)
    {
        $request->validate([
            'kota' => 'required|string'
        ]);

        $jumlah = DB::selectOne("SELECT sf_kamar_tersedia_kota(?) as jumlah", [$request->kota]);
        
        return response()->json([
            'success' => true,
            'kota' => $request->kota,
            'jumlah_kamar_tersedia' => $jumlah->jumlah
        ]);
    }
}