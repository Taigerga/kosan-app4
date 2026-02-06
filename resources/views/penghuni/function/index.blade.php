@extends('layouts.app')

@section('title', 'Demo Stored Function - Dashboard Penghuni')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Demo Stored Function Penghuni</h1>
                <p class="text-slate-400">Menggunakan MySQL Stored Function untuk informasi pribadi</p>
            </div>
            <div class="bg-gradient-to-r from-emerald-900/30 to-green-900/30 border border-emerald-700/30 rounded-xl p-4">
                <div class="text-sm text-emerald-300 mb-1">Total Pembayaran</div>
                <div class="text-2xl font-bold text-white">{{ $summary['total_pembayaran'] }}</div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-emerald-900/20 to-green-900/20 border border-emerald-700/30 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-info-circle text-emerald-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Stored Function untuk Penghuni</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Fungsi 1</div>
                        <div class="font-mono text-green-400">sf_total_pembayaran_penghuni()</div>
                        <div class="text-xs text-slate-500 mt-1">Hitung total pembayaran yang sudah lunas</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Fungsi 2</div>
                        <div class="font-mono text-green-400">sf_sisa_hari_kontrak()</div>
                        <div class="text-xs text-slate-500 mt-1">Hitung sisa hari kontrak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Pembayaran</div>
                <i class="fas fa-money-bill-wave text-green-400"></i>
            </div>
            <div class="text-xl font-bold text-white">{{ $summary['total_pembayaran'] }}</div>
            <button onclick="hitungTotalPembayaran()" 
                    class="mt-2 text-xs text-emerald-400 hover:text-emerald-300">
                <i class="fas fa-sync-alt mr-1"></i>Refresh
            </button>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Kontrak</div>
                <i class="fas fa-file-contract text-blue-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['total_kontrak'] }}</div>
            <div class="text-xs text-slate-500 mt-1">semua status</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Kontrak Aktif</div>
                <i class="fas fa-check-circle text-emerald-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['kontrak_aktif'] }}</div>
            <div class="text-xs text-slate-500 mt-1">sedang berjalan</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Rata Sisa Hari</div>
                <i class="fas fa-calendar-day text-yellow-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['rata_sisa_hari'] }}</div>
            <div class="text-xs text-slate-500 mt-1">hari</div>
        </div>
    </div>

    <!-- Demo Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Hitung Sisa Hari Kontrak -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Hitung Sisa Hari Kontrak</h3>
            <p class="text-slate-400 mb-4">Gunakan stored function <code class="text-emerald-400">sf_sisa_hari_kontrak()</code></p>
            
            @if($kontrakList->count() > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-400 mb-2">Pilih Kontrak</label>
                <select id="selectKontrak" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500">
                    @foreach($kontrakList as $kontrak)
                    <option value="{{ $kontrak->id_kontrak }}">
                        {{ $kontrak->kos->nama_kos }} - Kamar {{ $kontrak->kamar->nomor_kamar }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button onclick="hitungSisaHari()" 
                    class="w-full px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-500 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-green-600 transition-all duration-300">
                <i class="fas fa-calculator mr-2"></i>Hitung Sisa Hari
            </button>
            
            <div id="hasilSisaHari" class="hidden mt-4 bg-slate-900/50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-sm text-slate-400" id="kontrakLabel">Kontrak</div>
                        <div class="text-2xl font-bold text-white" id="sisaHariValue">0</div>
                        <div class="text-sm text-slate-400" id="statusSisaHari">hari lagi</div>
                    </div>
                    <div class="text-4xl" id="iconSisaHari">
                        <i class="fas fa-calendar-check text-emerald-400"></i>
                    </div>
                </div>
                <div class="text-xs text-slate-500">
                    Diambil menggunakan: <code class="text-emerald-400">SELECT sf_sisa_hari_kontrak(id_kontrak)</code>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i class="fas fa-file-contract text-4xl mb-3"></i>
                <p>Anda belum memiliki kontrak</p>
            </div>
            @endif
        </div>

        <!-- Info Stored Function -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Contoh Penggunaan dalam Query</h3>
            <div class="space-y-4">
                <div class="bg-slate-900/50 rounded-xl p-4">
                    <div class="text-sm text-slate-400 mb-1">Query 1: Total Pembayaran</div>
                    <div class="font-mono text-sm text-green-400 bg-black/30 p-2 rounded">
                        SELECT sf_total_pembayaran_penghuni({{ $penghuniId }}) AS total_bayar;
                    </div>
                </div>
                <div class="bg-slate-900/50 rounded-xl p-4">
                    <div class="text-sm text-slate-400 mb-1">Query 2: Sisa Hari Semua Kontrak</div>
                    <div class="font-mono text-sm text-green-400 bg-black/30 p-2 rounded">
                        SELECT id_kontrak, sf_sisa_hari_kontrak(id_kontrak) AS sisa_hari<br>
                        FROM kontrak_sewa WHERE id_penghuni = {{ $penghuniId }};
                    </div>
                </div>
                <div class="bg-slate-900/50 rounded-xl p-4">
                    <div class="text-sm text-slate-400 mb-1">Manfaat Stored Function</div>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                            <span>Perhitungan konsisten</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                            <span>Reduksi kompleksitas query</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                            <span>Mudah dipelihara</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                            <span>Performance optimal</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Kontrak dengan Sisa Hari -->
    @if($kontrakList->count() > 0)
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4">Data Kontrak dengan Sisa Hari (Stored Function)</h3>
        <p class="text-slate-400 mb-6">Sisa hari dihitung menggunakan stored function <code class="text-emerald-400">sf_sisa_hari_kontrak()</code></p>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kos & Kamar</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Periode</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Sewa</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Sisa Hari (SF)</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @foreach($kontrakList as $kontrak)
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-home text-emerald-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $kontrak->kos->nama_kos }}</div>
                                    <div class="text-sm text-slate-400">
                                        Kamar {{ $kontrak->kamar->nomor_kamar }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-white">
                                {{ \Carbon\Carbon::parse($kontrak->tanggal_mulai)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-slate-500">
                                s/d {{ \Carbon\Carbon::parse($kontrak->tanggal_selesai)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-lg font-bold text-white">
                                Rp {{ number_format($kontrak->harga_sewa, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $kontrak->durasi_sewa }} bulan
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($kontrak->sisa_hari !== null)
                            <div class="text-center">
                                <div class="text-2xl font-bold {{ $kontrak->status_warna == 'red' ? 'text-red-400' : ($kontrak->status_warna == 'yellow' ? 'text-yellow-400' : 'text-emerald-400') }}">
                                    {{ $kontrak->sisa_hari }}
                                </div>
                                <div class="text-xs text-slate-500">hari</div>
                            </div>
                            @else
                            <span class="text-sm text-slate-500">Tidak diketahui</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                {{ $kontrak->status_warna == 'red' ? 'bg-red-900/30 text-red-300' : 
                                   ($kontrak->status_warna == 'yellow' ? 'bg-yellow-900/30 text-yellow-300' : 
                                   'bg-emerald-900/30 text-emerald-300') }}">
                                {{ $kontrak->status_text }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <button onclick="hitungSisaHariKontrak({{ $kontrak->id_kontrak }})"
                                    class="px-3 py-1 text-xs bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-calculator mr-1"></i>Hitung
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Kontrak yang Segera Berakhir -->
    @if($summary['kontrak_mendatang'] > 0)
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4">Kontrak yang Segera Berakhir</h3>
        <p class="text-slate-400 mb-4">Kontrak dengan sisa hari kurang dari 30 hari</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($summary['kontrak_mendatang'], 3) }} gap-4">
            @foreach($kontrakList->filter(function($k) { return $k->sisa_hari !== null && $k->sisa_hari > 0 && $k->sisa_hari < 30; }) as $kontrak)
            <div class="bg-gradient-to-r from-yellow-900/20 to-amber-900/20 border border-yellow-700/30 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div>
                        <div class="font-medium text-white">{{ $kontrak->kos->nama_kos }}</div>
                        <div class="text-sm text-yellow-300">Kamar {{ $kontrak->kamar->nomor_kamar }}</div>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <div class="text-3xl font-bold text-white">{{ $kontrak->sisa_hari }}</div>
                    <div class="text-sm text-slate-400">hari lagi</div>
                </div>
                <div class="text-xs text-slate-400">
                    Berakhir: {{ \Carbon\Carbon::parse($kontrak->tanggal_selesai)->format('d M Y') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
        <a href="{{ route('penghuni.dashboard') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-emerald-500 hover:text-emerald-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <a href="{{ route('penghuni.procedure.detail') }}" 
           class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-500 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-green-600 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-database"></i>
            <span>Stored Procedure</span>
        </a>
        <a href="{{ route('penghuni.view.kontrak-saya') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-blue-500 hover:text-blue-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-eye"></i>
            <span>MySQL VIEW</span>
        </a>
    </div>
</div>

<script>
    function hitungSisaHari() {
        const idKontrak = document.getElementById('selectKontrak').value;
        
        fetch("{{ route('penghuni.function.hitung-sisa-hari') }}", {
            method: 'POST',
            body: new URLSearchParams({ id_kontrak: idKontrak }),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const hasilDiv = document.getElementById('hasilSisaHari');
                const kontrakLabel = document.getElementById('kontrakLabel');
                const sisaHariValue = document.getElementById('sisaHariValue');
                const statusSisaHari = document.getElementById('statusSisaHari');
                const iconSisaHari = document.getElementById('iconSisaHari');
                
                // Update values
                sisaHariValue.textContent = Math.abs(data.sisa_hari);
                statusSisaHari.textContent = data.status;
                
                // Update icon and color based on status
                if (data.sisa_hari < 0) {
                    sisaHariValue.className = 'text-2xl font-bold text-red-400';
                    iconSisaHari.innerHTML = '<i class="fas fa-calendar-times text-red-400"></i>';
                } else if (data.sisa_hari < 7) {
                    sisaHariValue.className = 'text-2xl font-bold text-yellow-400';
                    iconSisaHari.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-400"></i>';
                } else {
                    sisaHariValue.className = 'text-2xl font-bold text-emerald-400';
                    iconSisaHari.innerHTML = '<i class="fas fa-calendar-check text-emerald-400"></i>';
                }
                
                hasilDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghitung sisa hari');
        });
    }
    
    function hitungTotalPembayaran() {
        fetch("{{ route('penghuni.function.hitung-total-pembayaran') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Total pembayaran yang sudah lunas: ' + data.total_pembayaran);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghitung total pembayaran');
        });
    }
    
    function hitungSisaHariKontrak(idKontrak) {
        document.getElementById('selectKontrak').value = idKontrak;
        hitungSisaHari();
    }
</script>
@endsection