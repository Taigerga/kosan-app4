@extends('layouts.app')

@section('title', 'Ringkasan SP - AyoKos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 text-center">
        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-server text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white">Ringkasan (Stored Procedure)</h1>
                <p class="text-slate-400">Data statistik diambil menggunakan MySQL Stored Procedure</p>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-purple-900/20 to-pink-900/20 border border-purple-700/30 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-info-circle text-purple-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Stored Procedure</h3>
                <p class="text-slate-400 mb-3">
                    Data ini diambil menggunakan MySQL Stored Procedure <code>sp_ringkasan_umum()</code>.
                    Stored Procedure adalah kumpulan perintah SQL yang disimpan di database dan dapat dipanggil berulang kali.
                </p>
                <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-code"></i>
                        <span>Prosedur: sp_ringkasan_umum()</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock"></i>
                        <span>Eksekusi: {{ now()->format('H:i:s') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-server"></i>
                        <span>MySQL Stored Procedure</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($ringkasan)
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Kos -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-home text-blue-400"></i>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-blue-900/30 text-blue-300">
                    SP
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Total Kos Aktif</h3>
            <p class="text-3xl font-bold text-white mb-2">{{ $ringkasan->total_kos_aktif }}</p>
            <div class="grid grid-cols-3 gap-2 text-xs text-slate-500">
                <div class="text-center">
                    <div class="font-medium text-blue-300">{{ $ringkasan->kos_putra }}</div>
                    <div>Putra</div>
                </div>
                <div class="text-center">
                    <div class="font-medium text-pink-300">{{ $ringkasan->kos_putri }}</div>
                    <div>Putri</div>
                </div>
                <div class="text-center">
                    <div class="font-medium text-purple-300">{{ $ringkasan->kos_campuran }}</div>
                    <div>Campuran</div>
                </div>
            </div>
        </div>

        <!-- Kamar Tersedia -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bed text-green-400"></i>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-green-900/30 text-green-300">
                    SP
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Kamar Tersedia</h3>
            <p class="text-3xl font-bold text-white mb-2">{{ $ringkasan->total_kamar_tersedia }}</p>
            <p class="text-sm text-slate-400">Kamar kosong siap huni</p>
        </div>

        <!-- Pengguna Aktif -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-purple-400"></i>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-purple-900/30 text-purple-300">
                    SP
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Pengguna Aktif</h3>
            <div class="grid grid-cols-2 gap-4 mb-2">
                <div>
                    <div class="text-2xl font-bold text-white">{{ $ringkasan->total_pemilik_aktif }}</div>
                    <div class="text-xs text-slate-500">Pemilik</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white">{{ $ringkasan->total_penghuni_aktif }}</div>
                    <div class="text-xs text-slate-500">Penghuni</div>
                </div>
            </div>
        </div>

        <!-- Pendapatan & Rating -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-yellow-400"></i>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-yellow-900/30 text-yellow-300">
                    SP
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Finansial & Ulasan</h3>
            <div class="space-y-3">
                <div>
                    <div class="text-xl font-bold text-white">{{ $ringkasan->total_pendapatan_30hari }}</div>
                    <div class="text-xs text-slate-500">Pendapatan 30 hari</div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-bold text-white">{{ $ringkasan->rata_rata_rating }} ⭐</div>
                        <div class="text-xs text-slate-500">Rating rata-rata</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-white">{{ $ringkasan->total_review }}</div>
                        <div class="text-xs text-slate-500">Total ulasan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Kota Terpopuler -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-orange-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marked-alt text-orange-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-white">Kota Terpopuler</h3>
            </div>
            <p class="text-slate-400 mb-2">Kota dengan jumlah kos terbanyak:</p>
            <div class="bg-slate-900/50 rounded-xl p-4">
                <p class="text-xl font-bold text-white">{{ $ringkasan->kota_terpopuler }}</p>
            </div>
        </div>

        <!-- Database Info -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-server text-cyan-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-white">Database Information</h3>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Stored Procedure:</span>
                    <span class="text-green-400 font-mono">sp_ringkasan_umum()</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Eksekusi:</span>
                    <span class="text-white">{{ now()->format('d M Y H:i:s') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Metode:</span>
                    <span class="text-yellow-400">MySQL CALL</span>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-12 text-center">
        <div class="w-20 h-20 bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-database text-3xl text-slate-500"></i>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Data Tidak Tersedia</h3>
        <p class="text-slate-400 mb-6">Stored Procedure belum mengembalikan data atau terjadi kesalahan.</p>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
        <a href="{{ route('public.home') }}" 
           class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
            <i class="fas fa-home"></i>
            <span>Kembali ke Beranda</span>
        </a>
        <a href="{{ route('public.view.ringkasan') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-blue-500 hover:text-blue-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-eye"></i>
            <span>Lihat Versi VIEW</span>
        </a>
    </div>
</div>
@endsection