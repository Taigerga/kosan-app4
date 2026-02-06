@extends('layouts.app')

@section('title', 'Laporan Bulanan SP - Dashboard Pemilik')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Laporan Bulanan (Stored Procedure)</h1>
                <p class="text-slate-400">Data laporan keuangan bulanan diambil menggunakan MySQL Stored Procedure</p>
            </div>
            <div class="bg-gradient-to-r from-pink-900/30 to-rose-900/30 border border-pink-700/30 rounded-xl p-4">
                <div class="text-sm text-pink-300 mb-1">Total Pendapatan</div>
                <div class="text-2xl font-bold text-white">{{ $summary['total_pendapatan'] ?? 'Rp 0' }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <form action="{{ route('pemilik.procedure.laporan-bulanan') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-400 mb-2">Tahun</label>
                <select name="tahun" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-pink-500">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahunItem)
                    <option value="{{ $tahunItem }}" {{ $tahun == $tahunItem ? 'selected' : '' }}>{{ $tahunItem }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-400 mb-2">Bulan</label>
                <select name="bulan" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-pink-500">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all duration-300">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-pink-900/20 to-rose-900/20 border border-pink-700/30 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-pink-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-database text-pink-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Stored Procedure: sp_laporan_bulanan_pemilik()</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Procedure</div>
                        <div class="font-mono text-green-400">sp_laporan_bulanan_pemilik()</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Parameter 1</div>
                        <div class="text-white">id_pemilik = {{ Auth::guard('pemilik')->user()->id_pemilik }}</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Parameter 2 & 3</div>
                        <div class="text-white">tahun = {{ $tahun ?? 'NULL' }}, bulan = {{ $bulan ?? 'NULL' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Pendapatan</div>
                <i class="fas fa-money-bill-wave text-green-400"></i>
            </div>
            <div class="text-xl font-bold text-white">{{ $summary['total_pendapatan'] ?? 'Rp 0' }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Transaksi</div>
                <i class="fas fa-exchange-alt text-blue-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['total_transaksi'] ?? 0 }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Penghuni</div>
                <i class="fas fa-users text-emerald-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['total_penghuni'] ?? 0 }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Denda</div>
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <div class="text-xl font-bold text-white">{{ $summary['total_denda'] ?? 'Rp 0' }}</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-700 bg-slate-900/50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Laporan Bulanan per Kos</h2>
                <div class="text-sm text-slate-400">
                    Total: <span class="font-bold text-white">{{ count($laporan) }} Data</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kos</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Periode</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Transaksi</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Pendapatan</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Pembayaran</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Penghuni</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($laporan as $item)
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-pink-500/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-home text-pink-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $item->nama_kos }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ $item->nama_bulan ?? 'Semua Bulan' }} {{ $item->tahun ?? '' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-center">
                                @if($item->bulan && $item->tahun)
                                <div class="text-sm font-bold text-white">{{ $item->nama_bulan ?? $item->bulan }}</div>
                                <div class="text-xs text-slate-500">{{ $item->tahun }}</div>
                                @else
                                <span class="text-sm text-slate-500">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Total:</span>
                                    <span class="text-sm font-medium text-white">{{ $item->jumlah_transaksi }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Lunas:</span>
                                    <span class="text-xs font-medium text-green-400">{{ $item->transaksi_lunas }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Terlambat:</span>
                                    <span class="text-xs font-medium text-yellow-400">{{ $item->transaksi_terlambat }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Belum:</span>
                                    <span class="text-xs font-medium text-red-400">{{ $item->transaksi_belum }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-center">
                                <div class="text-lg font-bold text-white">{{ $item->total_pendapatan }}</div>
                                <div class="text-xs text-slate-500">Pendapatan bersih</div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col items-center gap-1">
                                @php
                                    $total_transaksi = $item->transaksi_lunas + $item->transaksi_terlambat + $item->transaksi_belum;
                                    $persentase_lunas = $total_transaksi > 0 ? ($item->transaksi_lunas / $total_transaksi) * 100 : 0;
                                @endphp
                                <div class="w-32 h-2 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500" style="width: {{ $persentase_lunas }}%"></div>
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ number_format($persentase_lunas, 1) }}% Lunas
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-center">
                                <div class="text-xl font-bold text-white">{{ $item->jumlah_penghuni }}</div>
                                <div class="text-xs text-slate-500">Penghuni aktif</div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="space-y-1">
                                <div class="text-sm font-medium text-red-300">{{ $item->denda_terlambat }}</div>
                                <div class="text-xs text-slate-500">
                                    Rata keterlambatan: {{ $item->rata_keterlambatan }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 px-6 text-center">
                            <div class="flex flex-col items-center justify-center gap-4 text-slate-500">
                                <div class="w-20 h-20 bg-slate-700/50 rounded-full flex items-center justify-center">
                                    <i class="fas fa-chart-bar text-3xl"></i>
                                </div>
                                <div>
                                    <p class="text-lg mb-2">Tidak ada data laporan</p>
                                    <p class="text-sm">Tidak ada transaksi untuk periode yang dipilih</p>
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
                    Menampilkan <span class="font-medium text-white">{{ count($laporan) }}</span> data laporan
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    <span>Data diambil dari SP sp_laporan_bulanan_pemilik()</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section (Placeholder) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Distribusi Pendapatan</h3>
            <div class="h-64 flex items-center justify-center">
                <div class="text-center text-slate-500">
                    <i class="fas fa-chart-pie text-4xl mb-3"></i>
                    <p>Chart akan ditampilkan di sini</p>
                    <p class="text-sm">(Integrasi chart library seperti Chart.js)</p>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Trend Pembayaran</h3>
            <div class="h-64 flex items-center justify-center">
                <div class="text-center text-slate-500">
                    <i class="fas fa-chart-line text-4xl mb-3"></i>
                    <p>Chart akan ditampilkan di sini</p>
                    <p class="text-sm">(Menampilkan trend bulanan)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Section -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mt-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Ekspor Laporan</h3>
                <p class="text-slate-400">Ekspor data laporan ke format lain</p>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i>
                    <span>PDF</span>
                </button>
                <button class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-excel"></i>
                    <span>Excel</span>
                </button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </button>
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
        <a href="{{ route('pemilik.procedure.analisis') }}" 
           class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-chart-bar"></i>
            <span>Analisis SP</span>
        </a>
        <a href="{{ route('pemilik.view.kos-analisis') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-emerald-500 hover:text-emerald-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-eye"></i>
            <span>Lihat Versi VIEW</span>
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