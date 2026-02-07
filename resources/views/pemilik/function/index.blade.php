@extends('layouts.app')

@section('title', 'Analisis Bisnis - Dashboard Pemilik')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                <i class="fa-solid fa-person mr-3"></i>    
                Analisis Bisnis Kos Anda</h1>
                <p class="text-slate-400">Pantau performa dan statistik bisnis kos Anda secara real-time</p>
            </div>
            <div class="bg-gradient-to-r from-blue-900/30 to-indigo-900/30 border border-blue-700/30 rounded-xl p-4">
                <div class="text-sm text-blue-300 mb-1">Pendapatan Bulan Ini</div>
                <div class="text-2xl font-bold text-white">{{ $summary['pendapatan_bulan_ini'] }}</div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-blue-900/20 to-indigo-900/20 border border-blue-700/30 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-lightbulb text-blue-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Fitur Analisis Bisnis</h3>
                <p class="text-slate-400 mb-3">Gunakan fitur perhitungan otomatis untuk melihat statistik bisnis kos Anda dengan mudah dan akurat.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Pendapatan</div>
                        <div class="font-medium text-cyan-400">Perhitungan Otomatis</div>
                        <div class="text-xs text-slate-500 mt-1">Hitung pendapatan bulan berjalan</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Okupansi</div>
                        <div class="font-medium text-cyan-400">Tingkat Penghunian</div>
                        <div class="text-xs text-slate-500 mt-1">Persentase kamar yang terisi</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Durasi Sewa</div>
                        <div class="font-medium text-cyan-400">Rata-rata Durasi</div>
                        <div class="text-xs text-slate-500 mt-1">Hitung rata-rata durasi kontrak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Pendapatan Bulan Ini</div>
                <i class="fas fa-money-bill-wave text-green-400"></i>
            </div>
            <div class="text-xl font-bold text-white">{{ $summary['pendapatan_bulan_ini'] }}</div>
            <button onclick="hitungPendapatan()" 
                    class="mt-2 text-xs text-blue-400 hover:text-blue-300">
                <i class="fas fa-sync-alt mr-1"></i>Perbarui
            </button>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Rata Durasi Sewa</div>
                <i class="fas fa-calendar-alt text-purple-400"></i>
            </div>
            <div class="text-xl font-bold text-white">{{ $summary['rata_durasi_sewa'] }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Kos</div>
                <i class="fas fa-home text-blue-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['total_kos'] }}</div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm text-slate-400">Total Kamar</div>
                <i class="fas fa-bed text-emerald-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $summary['total_kamar'] }}</div>
        </div>
    </div>

    <!-- Demo Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Hitung Okupansi -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Cek Tingkat Penghunian</h3>
            <p class="text-slate-400 mb-4">Lihat persentase kamar yang terisi untuk setiap kos Anda</p>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-400 mb-2">Pilih Kos</label>
                <select id="selectKos" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500">
                    @forelse($kosList as $kos)
                    <option value="{{ $kos->id_kos }}">{{ $kos->nama_kos }}</option>
                    @empty
                    <option value="">Tidak ada kos tersedia</option>
                    @endforelse
                </select>
            </div>
            @if($kosList->count() > 0)
            <button onclick="hitungOkupansi()" 
                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300">
                <i class="fas fa-chart-pie mr-2"></i>Cek Okupansi
            </button>
            @else
            <button disabled
                    class="w-full px-6 py-3 bg-slate-700 text-slate-400 font-semibold rounded-xl cursor-not-allowed">
                <i class="fas fa-chart-pie mr-2"></i>Tidak Ada Kos
            </button>
            @endif
            
            <div id="hasilOkupansi" class="hidden mt-4 bg-slate-900/50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-sm text-slate-400" id="namaKosOkupansi">Kos</div>
                        <div class="text-2xl font-bold text-white" id="persentaseOkupansi">0%</div>
                    </div>
                    <div class="relative w-20 h-20">
                        <svg class="w-20 h-20 transform -rotate-90">
                            <circle cx="40" cy="40" r="35" stroke="#1e293b" stroke-width="10" fill="none"/>
                            <circle id="okupansiCircle" cx="40" cy="40" r="35" stroke="#3b82f6" stroke-width="10" 
                                    fill="none" stroke-dasharray="219.91" stroke-dashoffset="219.91"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span id="okupansiText" class="text-lg font-bold text-white">0%</span>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-slate-500">
                    Persentase kamar yang terisi
                </div>
            </div>
        </div>

        <!-- Panduan -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Panduan Analisis</h3>
            <div class="space-y-4">
                <div class="bg-slate-900/50 rounded-xl p-4">
                    <div class="text-sm text-slate-400 mb-1">Tips Meningkatkan Okupansi</div>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-400 text-xs"></i>
                            <span>Tawarkan promo untuk kontrak jangka panjang</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-400 text-xs"></i>
                            <span>Tingkatkan kualitas fasilitas kos</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-400 text-xs"></i>
                            <span>Tambahkan foto kos yang menarik</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-400 text-xs"></i>
                            <span>Responsif dalam membalas inquiry</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-slate-900/50 rounded-xl p-4">
                    <div class="text-sm text-slate-400 mb-1">Keuntungan Analisis</div>
                    <ul class="text-sm text-slate-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-arrow-right text-blue-400 text-xs"></i>
                            <span>Pantau performa bisnis secara real-time</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-arrow-right text-blue-400 text-xs"></i>
                            <span>Identifikasi kos dengan okupansi rendah</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-arrow-right text-blue-400 text-xs"></i>
                            <span>Rencanakan strategi pemasaran yang tepat</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Kos dengan Okupansi -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4">Data Kos dan Tingkat Penghunian</h3>
        <p class="text-slate-400 mb-6">Overview lengkap performa semua kos Anda</p>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Kos</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Lokasi</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tingkat Penghunian</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Rating</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @foreach($kosList as $kos)
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-home text-blue-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $kos->nama_kos }}</div>
                                    <div class="text-xs text-slate-500">{{ $kos->kamar->count() }} kamar</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-white">{{ $kos->kota }}</div>
                            <div class="text-xs text-slate-500">{{ $kos->kecamatan }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <div class="flex justify-between text-xs text-slate-400 mb-1">
                                        <span>Penghunian</span>
                                        <span>{{ number_format($kos->okupansi, 1) }}%</span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full {{ $kos->okupansi >= 80 ? 'bg-red-500' : ($kos->okupansi >= 50 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                             style="width: {{ min($kos->okupansi, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($kos->rating > 0)
                            <div class="flex items-center text-yellow-400">
                                <i class="fas fa-star"></i>
                                <span class="ml-1 text-sm font-bold">{{ number_format($kos->rating, 1) }}</span>
                            </div>
                            @else
                            <span class="text-sm text-slate-500">Belum ada rating</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                {{ $kos->okupansi >= 90 ? 'bg-red-900/30 text-red-300' : 
                                   ($kos->okupansi >= 70 ? 'bg-yellow-900/30 text-yellow-300' : 
                                   'bg-green-900/30 text-green-300') }}">
                                @if($kos->okupansi >= 90)
                                Sangat Padat
                                @elseif($kos->okupansi >= 70)
                                Cukup Padat
                                @else
                                Masih Longgar
                                @endif
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <button onclick="hitungOkupansiKos({{ $kos->id_kos }})"
                                    class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-chart-pie mr-1"></i>Cek
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
            <span>Lihat Laporan</span>
        </a>
        <a href="{{ route('pemilik.view.kos-analisis') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-emerald-500 hover:text-emerald-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-list"></i>
            <span>Tabel Data</span>
        </a>
    </div>
</div>

<script>
    function hitungOkupansi() {
        const idKos = document.getElementById('selectKos').value;
        console.log('idKos:', idKos);
        
        if (!idKos) {
            alert('Pilih kos terlebih dahulu!');
            return;
        }
        
        const formData = new FormData();
        formData.append('id_kos', idKos);
        
        fetch("{{ route('pemilik.function.hitung-okupansi') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                const hasilDiv = document.getElementById('hasilOkupansi');
                const namaKos = document.getElementById('namaKosOkupansi');
                const persentase = document.getElementById('persentaseOkupansi');
                const okupansiText = document.getElementById('okupansiText');
                const okupansiCircle = document.getElementById('okupansiCircle');
                
                const okupansiValue = parseFloat(data.okupansi) || 0;
                
                namaKos.textContent = data.nama_kos;
                persentase.textContent = okupansiValue.toFixed(1) + '%';
                okupansiText.textContent = okupansiValue.toFixed(1) + '%';
                
                const circumference = 219.91;
                const offset = circumference - (okupansiValue / 100 * circumference);
                okupansiCircle.style.strokeDashoffset = offset;
                
                if (okupansiValue >= 80) {
                    okupansiCircle.style.stroke = '#ef4444';
                } else if (okupansiValue >= 50) {
                    okupansiCircle.style.stroke = '#f59e0b';
                } else {
                    okupansiCircle.style.stroke = '#10b981';
                }
                
                hasilDiv.classList.remove('hidden');
            } else {
                alert('Gagal: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghitung okupansi: ' + error.message);
        });
    }
    
    function hitungPendapatan() {
        fetch("{{ route('pemilik.function.hitung-pendapatan') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pendapatan bulan ini: ' + data.pendapatan);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghitung pendapatan');
        });
    }
    
    function hitungOkupansiKos(idKos) {
        document.getElementById('selectKos').value = idKos;
        hitungOkupansi();
    }
</script>
@endsection
