@extends('layouts.app')

@section('title', 'Detail SP - Dashboard Penghuni')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Detail Kontrak (Stored Procedure)</h1>
                <p class="text-slate-400">Data diambil menggunakan MySQL Stored Procedure sp_detail_penghuni()</p>
            </div>
            @if($summary['total_kontrak'] > 0)
            <div class="bg-gradient-to-r from-emerald-900/30 to-green-900/30 border border-emerald-700/30 rounded-xl p-4">
                <div class="text-sm text-emerald-300 mb-1">Kontrak Aktif</div>
                <div class="text-2xl font-bold text-white">{{ $summary['kontrak_aktif'] }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Filter Status Kontrak</h3>
                <p class="text-sm text-slate-400">Pilih status kontrak untuk difilter</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('penghuni.procedure.detail') }}" 
                   class="px-4 py-2 {{ !$statusKontrak ? 'bg-emerald-600 text-white' : 'bg-slate-700 text-slate-300' }} rounded-lg hover:bg-emerald-700 transition-colors">
                    Semua
                </a>
                <a href="{{ route('penghuni.procedure.detail', ['status' => 'aktif']) }}" 
                   class="px-4 py-2 {{ $statusKontrak == 'aktif' ? 'bg-emerald-600 text-white' : 'bg-slate-700 text-slate-300' }} rounded-lg hover:bg-emerald-700 transition-colors">
                    Aktif
                </a>
                <a href="{{ route('penghuni.procedure.detail', ['status' => 'pending']) }}" 
                   class="px-4 py-2 {{ $statusKontrak == 'pending' ? 'bg-yellow-600 text-white' : 'bg-slate-700 text-slate-300' }} rounded-lg hover:bg-yellow-700 transition-colors">
                    Pending
                </a>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-emerald-900/20 to-green-900/20 border border-emerald-700/30 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-database text-emerald-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Stored Procedure Parameter</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Procedure</div>
                        <div class="font-mono text-green-400">sp_detail_penghuni()</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Parameter</div>
                        <div class="text-white">
                            id_penghuni = {{ Auth::guard('penghuni')->user()->id_penghuni }}, 
                            status = {{ $statusKontrak ?? 'NULL' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($data->count() > 0)
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Dibayar</div>
                <i class="fas fa-money-bill-wave text-green-400"></i>
            </div>
            <div class="text-xl font-bold text-white">{{ $summary['total_dibayar'] }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Tunggakan</div>
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="text-xl font-bold text-white">{{ $summary['total_tunggakan'] }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Transaksi Lunas</div>
                <i class="fas fa-check-circle text-emerald-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['transaksi_lunas'] }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Transaksi Belum</div>
                <i class="fas fa-clock text-red-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['transaksi_belum'] }}</div>
        </div>
    </div>

    <!-- Kontrak Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @foreach($data as $item)
        <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden">
            <!-- Card Header -->
            <div class="p-6 border-b border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-home text-emerald-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">{{ $item->nama_kos }}</h3>
                            <p class="text-sm text-slate-400">Kamar {{ $item->nomor_kamar }} • {{ $item->tipe_kamar }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $item->status_badge }}">
                        {{ ucfirst($item->status_kontrak) }}
                    </span>
                </div>
                <p class="text-slate-400 text-sm">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ $item->alamat_kos }}, {{ $item->kota_kos }}
                </p>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Periode Kontrak -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-slate-400 mb-2">Periode Kontrak</h4>
                    <div class="flex items-center justify-between bg-slate-900/50 rounded-xl p-4">
                        <div class="text-center">
                            <div class="text-sm text-slate-400">Mulai</div>
                            <div class="text-lg font-bold text-white">{{ $item->tanggal_mulai_formatted }}</div>
                        </div>
                        <div class="text-slate-500">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-slate-400">Selesai</div>
                            <div class="text-lg font-bold text-white">{{ $item->tanggal_selesai_formatted }}</div>
                        </div>
                    </div>
                    @if($item->sisa_hari !== null)
                    <div class="mt-2 text-center">
                        <span class="text-sm {{ $item->status_kontrak_color == 'red' ? 'text-red-400' : ($item->status_kontrak_color == 'yellow' ? 'text-yellow-400' : 'text-emerald-400') }}">
                            <i class="fas fa-calendar-day mr-1"></i>
                            @if($item->sisa_hari < 0)
                            Kadaluarsa {{ abs($item->sisa_hari) }} hari lalu
                            @else
                            {{ $item->sisa_hari }} hari lagi
                            @endif
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Finansial -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-slate-900/30 rounded-xl p-4">
                        <div class="text-sm text-slate-400 mb-1">Harga Sewa</div>
                        <div class="text-xl font-bold text-white">{{ $item->harga_sewa_formatted }}</div>
                        <div class="text-xs text-slate-500">per {{ $item->durasi_sewa == 1 ? 'bulan' : ($item->durasi_sewa == 12 ? 'tahun' : 'periode') }}</div>
                    </div>
                    <div class="bg-slate-900/30 rounded-xl p-4">
                        <div class="text-sm text-slate-400 mb-1">Total Dibayar</div>
                        <div class="text-xl font-bold text-emerald-400">{{ $item->total_dibayar_formatted }}</div>
                        <div class="text-xs text-slate-500">{{ $item->transaksi_lunas }} transaksi lunas</div>
                    </div>
                </div>

                <!-- Pembayaran Status -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-slate-400 mb-2">Status Pembayaran</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">Lunas:</span>
                            <span class="text-sm font-medium text-emerald-400">{{ $item->transaksi_lunas }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">Belum:</span>
                            <span class="text-sm font-medium text-yellow-400">{{ $item->transaksi_belum }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">Terlambat:</span>
                            <span class="text-sm font-medium text-red-400">{{ $item->transaksi_terlambat }}</span>
                        </div>
                    </div>
                    @if($item->total_tunggakan > 0)
                    <div class="mt-3 p-3 bg-red-900/20 border border-red-700/30 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-red-300">Tunggakan:</span>
                            <span class="text-sm font-bold text-red-300">{{ $item->total_tunggakan_formatted }}</span>
                        </div>
                        @if($item->jatuh_tempo_formatted)
                        <div class="text-xs text-red-400 mt-1">
                            Jatuh tempo: {{ $item->jatuh_tempo_formatted }}
                            @if($item->sisa_jatuh_tempo < 0)
                            (Terlambat {{ abs($item->sisa_jatuh_tempo) }} hari)
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Fasilitas -->
                @if($item->fasilitas_kos)
                <div>
                    <h4 class="text-sm font-medium text-slate-400 mb-2">Fasilitas Kos</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_slice(explode(', ', $item->fasilitas_kos), 0, 5) as $fasilitas)
                        <span class="px-3 py-1 text-xs bg-slate-900/50 text-slate-300 rounded-full">
                            {{ trim($fasilitas) }}
                        </span>
                        @endforeach
                        @if(count(explode(', ', $item->fasilitas_kos)) > 5)
                        <span class="px-3 py-1 text-xs bg-purple-900/30 text-purple-300 rounded-full">
                            +{{ count(explode(', ', $item->fasilitas_kos)) - 5 }} lagi
                        </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-12 text-center">
        <div class="w-20 h-20 bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-file-contract text-3xl text-slate-500"></i>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Tidak ada kontrak</h3>
        <p class="text-slate-400 mb-6">
            @if($statusKontrak)
            Tidak ada kontrak dengan status "{{ $statusKontrak }}"
            @else
            Anda belum memiliki kontrak
            @endif
        </p>
        <a href="{{ route('public.kos.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
            <i class="fas fa-search"></i>
            <span>Cari Kos</span>
        </a>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
        <a href="{{ route('penghuni.dashboard') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-emerald-500 hover:text-emerald-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <a href="{{ route('penghuni.view.kontrak-saya') }}" 
           class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-500 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-green-600 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-eye"></i>
            <span>Lihat Versi VIEW</span>
        </a>
    </div>
</div>
@endsection