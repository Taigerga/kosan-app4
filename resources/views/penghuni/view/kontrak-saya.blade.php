@extends('layouts.app')

@section('title', 'Kontrak Saya - Dashboard Penghuni')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Kontrak dan Pembayaran Saya</h1>
                <p class="text-slate-400">Detail semua kontrak dan status pembayaran Anda</p>
            </div>
            @if($data->count() > 0)
            <div class="bg-gradient-to-r from-emerald-900/30 to-green-900/30 border border-emerald-700/30 rounded-xl p-4">
                <div class="text-sm text-emerald-300 mb-1">Total Kontrak Aktif</div>
                <div class="text-2xl font-bold text-white">
                    {{ $data->where('status_kontrak', 'aktif')->count() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-slate-700 bg-slate-900/50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Data Kontrak Saya</h2>
                <div class="text-sm text-slate-400">
                    Total: <span class="font-bold text-white">{{ $data->count() }} Kontrak</span>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kos & Kamar</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Periode</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Sewa</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Pembayaran</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Sisa Hari</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($data as $item)
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-home text-emerald-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $item->nama_kos }}</div>
                                    <div class="text-sm text-slate-400">
                                        Kamar: <span class="text-emerald-300">{{ $item->nomor_kamar }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <div class="text-sm text-white">{{ $item->tanggal_mulai_formatted }}</div>
                                <div class="text-xs text-slate-500">s/d {{ $item->tanggal_selesai_formatted }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-lg font-bold text-white">{{ $item->harga_sewa_formatted }}</div>
                            <div class="text-xs text-slate-500">per periode</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">Lunas:</span>
                                    <span class="text-sm font-medium text-green-400">{{ $item->pembayaran_lunas }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">Belum:</span>
                                    <span class="text-sm font-medium text-yellow-400">{{ $item->pembayaran_belum }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">Terlambat:</span>
                                    <span class="text-sm font-medium text-red-400">{{ $item->pembayaran_terlambat }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-center">
                                @if($item->sisa_hari >= 0)
                                <div class="text-2xl font-bold {{ $item->status_warna }}">{{ $item->sisa_hari }}</div>
                                <div class="text-xs text-slate-500">hari lagi</div>
                                @else
                                <div class="text-lg font-bold text-red-500">Berakhir</div>
                                <div class="text-xs text-red-400">{{ abs($item->sisa_hari) }} hari lalu</div>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col gap-2">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $item->status_kontrak == 'aktif' ? 'bg-green-900/30 text-green-300' : 
                                       ($item->status_kontrak == 'pending' ? 'bg-yellow-900/30 text-yellow-300' : 
                                       'bg-red-900/30 text-red-300') }}">
                                    {{ ucfirst($item->status_kontrak) }}
                                </span>
                                <div class="text-xs text-slate-500 {{ $item->status_warna }}">
                                    {{ $item->status_text }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 px-6 text-center">
                            <div class="flex flex-col items-center justify-center gap-4 text-slate-500">
                                <div class="w-20 h-20 bg-slate-700/50 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-contract text-3xl"></i>
                                </div>
                                <div>
                                    <p class="text-lg mb-2">Belum ada kontrak</p>
                                    <p class="text-sm mb-4">Ajukan kontrak baru untuk mulai menempati kos</p>
                                    <a href="{{ route('public.kos.index') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                        <i class="fas fa-search"></i>
                                        <span>Cari Kos</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="px-6 py-4 border-t border-slate-700 bg-slate-900/30">
            <div class="flex items-center justify-between text-sm text-slate-400">
                <div>
                    Menampilkan <span class="font-medium text-white">{{ $data->count() }}</span> data kontrak
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    <span>Data diambil dari VIEW v_dashboard_penghuni</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fasilitas Section -->
    @if($data->count() > 0)
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-white mb-4">Fasilitas Kos yang Anda Tempati</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php
                $allFacilities = [];
                foreach($data as $item) {
                    if($item->fasilitas_kos) {
                        $facilities = explode(', ', $item->fasilitas_kos);
                        $allFacilities = array_merge($allFacilities, $facilities);
                    }
                }
                $uniqueFacilities = array_unique($allFacilities);
            @endphp
            
            @foreach(array_slice($uniqueFacilities, 0, 8) as $facility)
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-3 flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-blue-400 text-xs"></i>
                </div>
                <span class="text-sm text-white truncate">{{ $facility }}</span>
            </div>
            @endforeach
            
            @if(count($uniqueFacilities) > 8)
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-3 flex items-center gap-2">
                <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-plus text-purple-400 text-xs"></i>
                </div>
                <span class="text-sm text-white">+{{ count($uniqueFacilities) - 8 }} lagi</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="mt-8 text-center">
        <a href="{{ route('penghuni.dashboard') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-emerald-500 hover:text-emerald-300 transition-all duration-300">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
</div>

<style>
    table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    th {
        position: sticky;
        top: 0;
        background-color: #1e293b;
        z-index: 10;
    }
    
    tbody tr:last-child td {
        border-bottom: none;
    }
</style>
@endsection