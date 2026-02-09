@extends('layouts.app')

@section('title', 'Analisis Data - Dashboard Pemilik')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                <i class="fas fa-database mr-3"></i>    
                Analisis Data Kos Anda</h1>
                <p class="text-slate-400">Lihat laporan lengkap performa dan statistik bisnis kos Anda</p>
            </div>
            <div class="bg-gradient-to-r from-purple-900/30 to-indigo-900/30 border border-purple-700/30 rounded-xl p-4">
                <div class="text-sm text-purple-300 mb-1">Total Pendapatan</div>
                <div class="text-2xl font-bold text-white">{{ $totalPendapatan ?? 'Rp 0' }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <form action="{{ route('pemilik.procedure.analisis') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-400 mb-2">Tahun</label>
                <select name="tahun" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahunItem)
                    <option value="{{ $tahunItem }}" {{ $tahun == $tahunItem ? 'selected' : '' }}>{{ $tahunItem }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-400 mb-2">Bulan</label>
                <select name="bulan" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ (int)$bulan == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-blue-900/20 to-indigo-900/20 border border-blue-700/30 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Laporan Analisis Lengkap</h3>
                <p class="text-slate-400 mb-3">Halaman ini menampilkan laporan lengkap analisis data kos Anda. Filter berdasarkan tahun dan bulan untuk melihat data spesifik.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Data Kos</div>
                        <div class="text-white">Informasi lengkap kos Anda</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Periode</div>
                        <div class="text-white">{{ $bulan ? \Carbon\Carbon::create()->month((int)$bulan)->locale('id')->monthName : 'Semua' }} {{ $tahun ?? 'Semua' }}</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Status</div>
                        <div class="text-green-400">Data tersedia</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-700 bg-slate-900/50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Data Kos</h2>
                <div class="text-sm text-slate-400">
                    Total: <span class="font-bold text-white">{{ count($data) }} Kos</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kos</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kamar</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kontrak</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Pendapatan</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Rating</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Harga</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($data as $item)
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-home text-blue-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $item->nama_kos }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->kota }}</div>
                                    <div class="text-xs text-slate-500 capitalize">{{ $item->jenis_kos }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-400">Total:</span>
                                    <span class="text-sm font-medium text-white">{{ $item->total_kamar }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-400">Tersedia:</span>
                                    <span class="text-sm font-medium text-green-400">{{ $item->kamar_tersedia }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-400">Terisi:</span>
                                    <span class="text-sm font-medium text-blue-400">{{ $item->kamar_terisi }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-400">Maintenance:</span>
                                    <span class="text-sm font-medium text-yellow-400">{{ $item->kamar_maintenance }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-400">Total:</span>
                                    <span class="text-sm font-medium text-white">{{ $item->total_kontrak }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-400">Aktif:</span>
                                    <span class="text-sm font-medium text-green-400">{{ $item->kontrak_aktif }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-400">Pending:</span>
                                    <span class="text-sm font-medium text-yellow-400">{{ $item->kontrak_pending }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-center">
                                <div class="text-lg font-bold text-white">{{ $item->pendapatan_periode }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $item->pembayaran_terakhir }}
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-center">
                                @if($item->rating_rata_rata > 0)
                                <div class="flex items-center justify-center text-yellow-400 mb-1">
                                    <i class="fas fa-star"></i>
                                    <span class="ml-1 text-lg font-bold">{{ $item->rating_rata_rata }}</span>
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $item->jumlah_review }} ulasan
                                </div>
                                @else
                                <span class="text-sm text-slate-500">Belum ada rating</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Terendah:</span>
                                    <span class="text-xs font-medium text-green-400">{{ $item->harga_terendah }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Tertinggi:</span>
                                    <span class="text-xs font-medium text-blue-400">{{ $item->harga_tertinggi }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col items-center gap-2">
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $item->persentase_terisi == '100.0%' ? 'bg-red-900/30 text-red-300' : 'bg-green-900/30 text-green-300' }}">
                                    {{ $item->persentase_terisi }} Terisi
                                </span>
                                <div class="w-32 h-2 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full {{ $item->persentase_terisi == '100.0%' ? 'bg-red-500' : 'bg-green-500' }}" 
                                         style="width: {{ $item->persentase_terisi }}"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 px-6 text-center">
                            <div class="flex flex-col items-center justify-center gap-4 text-slate-500">
                                <div class="w-20 h-20 bg-slate-700/50 rounded-full flex items-center justify-center">
                                    <i class="fas fa-folder-open text-3xl"></i>
                                </div>
                                <div>
                                    <p class="text-lg mb-2">Tidak ada data</p>
                                    <p class="text-sm">Tidak ada data kos untuk periode yang dipilih</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Kos</div>
                <i class="fas fa-home text-blue-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ count($data) }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Kamar</div>
                <i class="fas fa-bed text-green-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ collect($data)->sum('total_kamar') }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Kontrak Aktif</div>
                <i class="fas fa-file-contract text-purple-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ collect($data)->sum('kontrak_aktif') }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Avg Rating</div>
                <i class="fas fa-star text-yellow-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">
                {{ number_format(collect($data)->avg('rating_rata_rata') ?? 0, 1) }}
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
        <a href="{{ route('pemilik.dashboard') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-blue-500 hover:text-blue-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <a href="{{ route('pemilik.view.kos-analisis') }}" 
           class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-table"></i>
            <span>Tabel Data</span>
        </a>
        <a href="{{ route('pemilik.procedure.laporan-bulanan') }}" 
           class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-xl hover:from-purple-600 hover:to-pink-600 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Laporan Keuangan</span>
        </a>
    </div>
</div>
@endsection
