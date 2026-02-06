<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcedureController extends Controller
{
    public function detailSp(Request $request)
    {
        $penghuniId = Auth::guard('penghuni')->user()->id_penghuni;
        
        $statusKontrak = $request->input('status', null);

        // Panggil stored procedure dengan parameter
        $data = DB::select('CALL sp_detail_penghuni(?, ?)', [
            $penghuniId,
            $statusKontrak
        ]);

        // Format data
        $data = collect($data)->map(function ($item) {
            // Format tanggal
            $item->tanggal_daftar_formatted = Carbon::parse($item->tanggal_daftar)->format('d M Y');
            $item->tanggal_mulai_formatted = Carbon::parse($item->tanggal_mulai)->format('d M Y');
            $item->tanggal_selesai_formatted = Carbon::parse($item->tanggal_selesai)->format('d M Y');
            
            // Hitung status kontrak
            if ($item->sisa_hari !== null) {
                if ($item->sisa_hari < 0) {
                    $item->status_kontrak_color = 'red';
                    $item->status_kontrak_text = 'Kadaluarsa';
                    $item->status_badge = 'bg-red-900/30 text-red-300';
                } elseif ($item->sisa_hari < 7) {
                    $item->status_kontrak_color = 'yellow';
                    $item->status_kontrak_text = 'Segera berakhir';
                    $item->status_badge = 'bg-yellow-900/30 text-yellow-300';
                } else {
                    $item->status_kontrak_color = 'green';
                    $item->status_kontrak_text = 'Aktif';
                    $item->status_badge = 'bg-green-900/30 text-green-300';
                }
            } else {
                $item->status_kontrak_color = 'green';
                $item->status_kontrak_text = 'Aktif';
                $item->status_badge = 'bg-green-900/30 text-green-300';
            }
            
            // Format uang
            $item->harga_kamar_formatted = 'Rp ' . number_format($item->harga_kamar, 0, ',', '.');
            $item->harga_sewa_formatted = 'Rp ' . number_format($item->harga_sewa, 0, ',', '.');
            $item->total_dibayar_formatted = 'Rp ' . number_format($item->total_dibayar, 0, ',', '.');
            $item->total_tunggakan_formatted = 'Rp ' . number_format($item->total_tunggakan, 0, ',', '.');
            
            // Format jatuh tempo
            if ($item->jatuh_tempo_terdekat) {
                $item->jatuh_tempo_formatted = Carbon::parse($item->jatuh_tempo_terdekat)->format('d M Y');
                $item->sisa_jatuh_tempo = Carbon::parse($item->jatuh_tempo_terdekat)->diffInDays(Carbon::now(), false);
            }
            
            // Rating
            $item->rating_kos_formatted = number_format($item->rating_kos, 1);
            
            return $item;
        });

        // Hitung summary
        $summary = [
            'total_kontrak' => $data->count(),
            'kontrak_aktif' => $data->where('status_kontrak', 'aktif')->count(),
            'total_dibayar' => 'Rp ' . number_format($data->sum('total_dibayar'), 0, ',', '.'),
            'total_tunggakan' => 'Rp ' . number_format($data->sum('total_tunggakan'), 0, ',', '.'),
            'transaksi_lunas' => $data->sum('transaksi_lunas'),
            'transaksi_belum' => $data->sum('transaksi_belum'),
        ];

        return view('penghuni.procedure.detail-sp', compact('data', 'summary', 'statusKontrak'));
    }
}