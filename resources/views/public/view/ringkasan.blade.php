@extends('layouts.app')

@section('title', 'Ringkasan Umum - AyoKos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8 text-center">
        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-pie text-white text-xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">Ringkasan Platform AyoKos</h1>
        </div>
        <p class="text-slate-400">Statistik dan data overview platform kos terbaik Indonesia</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Kos Aktif -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-home text-blue-400 text-lg"></i>
                </div>
                <span class="text-xs font-medium px-3 py-1 rounded-full bg-blue-900/30 text-blue-300">
                    <i class="fas fa-arrow-up mr-1"></i> Aktif
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Total Kos Aktif</h3>
            <p class="text-3xl font-bold text-white mb-2">{{ $ringkasan->total_kos_aktif ?? '0' }}</p>
            <p class="text-sm text-slate-400">Kos terdaftar dan aktif di platform</p>
        </div>

        <!-- Kamar Tersedia -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bed text-green-400 text-lg"></i>
                </div>
                <span class="text-xs font-medium px-3 py-1 rounded-full bg-green-900/30 text-green-300">
                    <i class="fas fa-check mr-1"></i> Tersedia
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Kamar Tersedia</h3>
            <p class="text-3xl font-bold text-white mb-2">{{ $ringkasan->total_kamar_tersedia ?? '0' }}</p>
            <p class="text-sm text-slate-400">Kamar kosong siap dihuni</p>
        </div>

        <!-- Total Pemilik -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-purple-400 text-lg"></i>
                </div>
                <span class="text-xs font-medium px-3 py-1 rounded-full bg-purple-900/30 text-purple-300">
                    <i class="fas fa-users mr-1"></i> Terdaftar
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Total Pemilik</h3>
            <p class="text-3xl font-bold text-white mb-2">{{ $ringkasan->total_pemilik_aktif ?? '0' }}</p>
            <p class="text-sm text-slate-400">Pemilik kos terverifikasi</p>
        </div>

        <!-- Total Penghuni -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-emerald-400 text-lg"></i>
                </div>
                <span class="text-xs font-medium px-3 py-1 rounded-full bg-emerald-900/30 text-emerald-300">
                    <i class="fas fa-home mr-1"></i> Menetap
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Total Penghuni</h3>
            <p class="text-3xl font-bold text-white mb-2">{{ $ringkasan->total_penghuni_aktif ?? '0' }}</p>
            <p class="text-sm text-slate-400">Penghuni aktif saat ini</p>
        </div>

        <!-- Pendapatan 30 Hari -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-yellow-400 text-lg"></i>
                </div>
                <span class="text-xs font-medium px-3 py-1 rounded-full bg-yellow-900/30 text-yellow-300">
                    30 Hari
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Pendapatan 30 Hari</h3>
            <p class="text-3xl font-bold text-white mb-2">{{ $ringkasan->total_pendapatan_30hari ?? 'Rp 0' }}</p>
            <p class="text-sm text-slate-400">Total transaksi terakhir bulan</p>
        </div>

        <!-- Kota Terpopuler -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-orange-400 text-lg"></i>
                </div>
                <span class="text-xs font-medium px-3 py-1 rounded-full bg-orange-900/30 text-orange-300">
                    Terpopuler
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Kota Terpopuler</h3>
            <p class="text-xl font-bold text-white mb-2 truncate">{{ $ringkasan->kota_terpopuler ?? '-' }}</p>
            <p class="text-sm text-slate-400">Lokasi kos paling diminati</p>
        </div>
    </div>

    <!-- Info Section -->
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 border border-slate-700 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Tentang Data Statistik</h3>
                <p class="text-slate-400 mb-3">
                    Data statistik ini diperbarui secara real-time dari sistem AyoKos.
                    Menampilkan informasi terkini tentang jumlah kos, kamar tersedia,
                    dan aktivitas pengguna di platform kami.
                </p>
                <div class="flex items-center gap-4 text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i>
                        <span>Diperbarui: {{ now()->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-400"></i>
                        <span>Data akurat dan terpercaya</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
        <a href="{{ route('public.home') }}" 
           class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
            <i class="fas fa-home"></i>
            <span>Kembali ke Beranda</span>
        </a>
        <a href="{{ route('public.kos.index') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-blue-500 hover:text-blue-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-search"></i>
            <span>Cari Kos</span>
        </a>
    </div>
</div>

<style>
    .card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection