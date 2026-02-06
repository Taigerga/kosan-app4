@extends('layouts.app')

@section('title', 'Informasi Saya - Dashboard Penghuni')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                    <i class="fa-solid fa-person mr-3"></i>
                    Informasi Pribadi Saya</h1>
                <p class="text-slate-400">Lihat ringkasan kontrak dan pembayaran Anda</p>
            </div>
            <div class="bg-gradient-to-r from-emerald-900/30 to-green-900/30 border border-emerald-700/30 rounded-xl p-4">
                <div class="text-sm text-emerald-300 mb-1">Total Pembayaran</div>
                <div class="text-2xl font-bold text-white">{{ $summary['total_pembayaran'] }}</div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-emerald-900/20 to-green-900/20 border border-emerald-700/30 rounded-2xl p-6 mb-8">
        <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-emerald-400 text-xl"></i>
            </div>
            
            <div class="text-center w-full">
                <h3 class="text-lg font-semibold text-white mb-2">Informasi Kontrak & Pembayaran</h3>
                <p class="text-slate-400 mb-6 max-w-2xl mx-auto text-sm md:text-base">
                    Halaman ini menampilkan informasi lengkap tentang kontrak dan pembayaran Anda. 
                    Gunakan fitur perhitungan otomatis untuk melihat statistik Anda.
                </p>
                
                <div class="grid grid-cols-2 gap-4 max-w-xl mx-auto">
                    <div class="bg-slate-900/60 border border-slate-700/50 rounded-xl p-4 flex flex-col items-center justify-center transition-all hover:border-emerald-500/50">
                        <div class="text-xs md:text-sm text-emerald-400 font-medium mb-1 uppercase tracking-wider">Total Pembayaran</div>
                        <div class="text-white text-sm md:text-base font-semibold leading-tight">
                            {{ $summary['total_dibayar'] ?? 'Lunas' }}
                        </div>
                        <div class="text-[10px] text-slate-500 mt-1 italic">Sudah terverifikasi</div>
                    </div>

                    <div class="bg-slate-900/60 border border-slate-700/50 rounded-xl p-4 flex flex-col items-center justify-center transition-all hover:border-emerald-500/50">
                        <div class="text-xs md:text-sm text-emerald-400 font-medium mb-1 uppercase tracking-wider">Sisa Hari Kontrak</div>
                        <div class="text-white text-sm md:text-base font-semibold leading-tight">
                            @if(isset($data[0]) && $data[0]->sisa_hari !== null)
                                {{ $data[0]->sisa_hari }} Hari Lagi
                            @else
                                Aktif
                            @endif
                        </div>
                        <div class="text-[10px] text-slate-500 mt-1 italic">Update otomatis</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 ">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 ">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Pembayaran</div>
                <i class="fas fa-money-bill-wave text-green-400"></i>
            </div>
            <div class="text-xl font-bold text-white">{{ $summary['total_pembayaran'] }}</div>
            <button onclick="hitungTotalPembayaran()" 
                    class="mt-2 text-xs text-emerald-400 hover:text-emerald-300">
                <i class="fas fa-sync-alt mr-1"></i>Perbarui
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
        <!-- Cek Sisa Hari Kontrak -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Cek Sisa Hari Kontrak</h3>
            <p class="text-slate-400 mb-4">Lihat berapa lama lagi kontrak Anda akan berakhir</p>
            
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
                <i class="fas fa-calendar-check mr-2"></i>Cek Sisa Hari
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
                    Informasi kontrak aktif
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i class="fas fa-file-contract text-4xl mb-3"></i>
                <p>Anda belum memiliki kontrak</p>
            </div>
            @endif
        </div>

        <!-- Panduan -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Tips Menjaga Kontrak</h3>
            <div class="space-y-4">
                <div class="bg-slate-900/50 rounded-xl p-4">
                    <div class="text-sm text-slate-400 mb-1">Tips Pembayaran Tepat Waktu</div>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                            <span>Bayar sebelum tanggal jatuh tempo</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                            <span>Simpan bukti pembayaran dengan baik</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                            <span>Hubungi pemilik jika ada kendala</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-slate-900/50 rounded-xl p-4">
                    <div class="text-sm text-slate-400 mb-1">Manfaat Monitoring Kontrak</div>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-arrow-right text-blue-400 text-xs"></i>
                            <span>Ketahui kapan kontrak berakhir</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-arrow-right text-blue-400 text-xs"></i>
                            <span>Prencanakan perpanjangan kontrak</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-arrow-right text-blue-400 text-xs"></i>
                            <span>Kontrol keuangan dengan baik</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Kontrak dengan Sisa Hari -->
    @if($kontrakList->count() > 0)
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4">Data Kontrak Saya</h3>
        <p class="text-slate-400 mb-6">Overview lengkap semua kontrak Anda</p>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kos & Kamar</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Periode</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Sewa</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Sisa Hari</th>
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
                                <i class="fas fa-calendar-check mr-1"></i>Cek
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
            <i class="fas fa-file-alt"></i>
            <span>Detail Kontrak</span>
        </a>
        <a href="{{ route('penghuni.view.kontrak-saya') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-blue-500 hover:text-blue-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-list"></i>
            <span>Tabel Kontrak</span>
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
                
                sisaHariValue.textContent = Math.abs(data.sisa_hari);
                statusSisaHari.textContent = data.status;
                
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