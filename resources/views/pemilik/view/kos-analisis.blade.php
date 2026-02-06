@extends('layouts.app')

@section('title', 'Analisis Kos - Dashboard Pemilik')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Data Analisis Kos</h1>
                <p class="text-slate-400">Lihat data lengkap semua kos milik Anda dalam format tabel</p>
            </div>
            <div class="bg-gradient-to-r from-blue-900/30 to-indigo-900/30 border border-blue-700/30 rounded-xl p-4">
                <div class="text-sm text-blue-300 mb-1">Total Pendapatan Bulan Ini</div>
                <div class="text-2xl font-bold text-white">{{ $totalPendapatan ?? 'Rp 0' }}</div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-slate-700 bg-slate-900/50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Data Kos Saya</h2>
                <div class="text-sm text-slate-400">
                    Total: <span class="font-bold text-white">{{ $data->count() }} Kos</span>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Kos</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Lokasi</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kamar</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kontrak</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Pendapatan</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Rating</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($data as $item)
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-home text-blue-400 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $item->nama_kos }}</div>
                                    <div class="text-xs text-slate-500 capitalize">{{ $item->jenis_kos }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-white">{{ $item->kota }}</div>
                            <div class="text-xs text-slate-500 truncate max-w-[200px]">{{ $item->alamat }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">Total:</span>
                                    <span class="text-sm font-medium text-white">{{ $item->total_kamar }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">Tersedia:</span>
                                    <span class="text-sm font-medium text-green-400">{{ $item->kamar_tersedia }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">Terisi:</span>
                                    <span class="text-sm font-medium text-blue-400">{{ $item->kamar_terisi }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-center">
                                <div class="text-lg font-bold text-white">{{ $item->total_kontrak_aktif }}</div>
                                <div class="text-xs text-slate-500">Aktif</div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <div class="text-sm font-medium text-white">{{ $item->pendapatan_bulan_ini }}</div>
                                <div class="text-xs text-slate-500">Bulan ini</div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                @if($item->rating_rata_rata > 0)
                                <div class="flex items-center text-yellow-400">
                                    <i class="fas fa-star text-sm"></i>
                                    <span class="ml-1 text-sm font-medium">{{ $item->rating_rata_rata }}</span>
                                </div>
                                @else
                                <span class="text-sm text-slate-500">Belum ada rating</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($item->kamar_tersedia > 0)
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-900/30 text-green-300">
                                <i class="fas fa-check-circle mr-1"></i> Tersedia
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-900/30 text-red-300">
                                <i class="fas fa-times-circle mr-1"></i> Penuh
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center">
                            <div class="flex flex-col items-center justify-center gap-3 text-slate-500">
                                <div class="w-16 h-16 bg-slate-700/50 rounded-full flex items-center justify-center">
                                    <i class="fas fa-home text-2xl"></i>
                                </div>
                                <p class="text-lg">Belum ada data kos</p>
                                <p class="text-sm">Tambahkan kos pertama Anda untuk melihat analisis</p>
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
                    Menampilkan <span class="font-medium text-white">{{ $data->count() }}</span> data kos
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    <span>Data aktual dari sistem</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-400"></i>
                </div>
                <div>
                    <div class="text-sm text-slate-400">Rata-rata Rating</div>
                    <div class="text-xl font-bold text-white">
                        {{ number_format($data->avg('rating_rata_rata') ?? 0, 1) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bed text-green-400"></i>
                </div>
                <div>
                    <div class="text-sm text-slate-400">Total Kamar Tersedia</div>
                    <div class="text-xl font-bold text-white">
                        {{ $data->sum('kamar_tersedia') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-contract text-purple-400"></i>
                </div>
                <div>
                    <div class="text-sm text-slate-400">Total Kontrak Aktif</div>
                    <div class="text-xl font-bold text-white">
                        {{ $data->sum('total_kontrak_aktif') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 text-center">
        <a href="{{ route('pemilik.dashboard') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-blue-500 hover:text-blue-300 transition-all duration-300">
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
