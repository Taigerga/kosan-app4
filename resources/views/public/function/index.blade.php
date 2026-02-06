@extends('layouts.app')

@section('title', 'Fitur Pencarian - AyoKos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 text-center">
        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-search text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white">Cari & Hitung Data Kos</h1>
                <p class="text-slate-400">Temukan kos terbaik dan lihat informasi lengkapnya</p>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-cyan-900/20 to-teal-900/20 border border-cyan-700/30 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-lightbulb text-cyan-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Fitur Pencarian Cerdas</h3>
                <p class="text-slate-400 mb-3">
                    Gunakan fitur pencarian untuk menemukan informasi kos dengan cepat.
                    Anda bisa melihat rating kos dan ketersediaan kamar di setiap kota.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Cek Rating</div>
                        <div class="font-mono text-cyan-400">Rating Kos</div>
                        <div class="text-xs text-slate-500 mt-1">Lihat rating rata-rata dari penghuni</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-3">
                        <div class="text-sm text-slate-400 mb-1">Cek Ketersediaan</div>
                        <div class="font-mono text-cyan-400">Kamar per Kota</div>
                        <div class="text-xs text-slate-500 mt-1">Lihat jumlah kamar tersedia di kota</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Form Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Cek Rating Kos -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Cek Rating Kos</h3>
            <p class="text-slate-400 mb-4">Lihat rating dan ulasan dari penghuni kos</p>
            
            <form id="formCekRating" class="mb-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-400 mb-2">Pilih Kos</label>
                    <select name="id_kos" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                        @foreach($kosList as $kos)
                        <option value="{{ $kos->id_kos }}">{{ $kos->nama_kos }} - {{ $kos->kota }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="cekRating()" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-500 text-white font-semibold rounded-xl hover:from-cyan-600 hover:to-teal-600 transition-all duration-300">
                    <i class="fas fa-star mr-2"></i>Lihat Rating
                </button>
            </form>
            
            <div id="hasilRating" class="hidden bg-slate-900/50 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-400">Rating Kos</div>
                        <div class="text-2xl font-bold text-white" id="ratingValue">0.0</div>
                    </div>
                    <div class="text-3xl text-yellow-400" id="ratingStars">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="text-xs text-slate-500 mt-2">
                    Berdasarkan ulasan penghuni
                </div>
            </div>
        </div>

        <!-- Cek Kamar Tersedia di Kota -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Cek Kamar Tersedia per Kota</h3>
            <p class="text-slate-400 mb-4">Lihat jumlah kamar yang masih tersedia di setiap kota</p>
            
            <form id="formCekKota" class="mb-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-400 mb-2">Pilih Kota</label>
                    <select name="kota" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-cyan-500">
                        @php $uniqueKotas = $kosList->unique('kota')->pluck('kota'); @endphp
                        @foreach($uniqueKotas as $kota)
                        <option value="{{ $kota }}">{{ $kota }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="cekKota()" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-500 text-white font-semibold rounded-xl hover:from-cyan-600 hover:to-teal-600 transition-all duration-300">
                    <i class="fas fa-search-location mr-2"></i>Cek Ketersediaan
                </button>
            </form>
            
            <div id="hasilKota" class="hidden bg-slate-900/50 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-400" id="kotaLabel">Kota</div>
                        <div class="text-2xl font-bold text-white" id="jumlahKamar">0</div>
                        <div class="text-sm text-slate-400">kamar tersedia</div>
                    </div>
                    <div class="text-3xl text-cyan-400">
                        <i class="fas fa-bed"></i>
                    </div>
                </div>
                <div class="text-xs text-slate-500 mt-2">
                    Tersedia untuk disewa
                </div>
            </div>
        </div>
    </div>

    <!-- Data Kos dengan Rating -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4">Daftar Kos Terdaftar</h3>
        <p class="text-slate-400 mb-6">Lihat informasi lengkap kos yang tersedia di platform kami</p>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900/30">
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Kos</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Lokasi</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Rating</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Kamar</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @foreach($kosList as $kos)
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-home text-cyan-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $kos->nama_kos }}</div>
                                    <div class="text-xs text-slate-500 capitalize">{{ $kos->jenis_kos }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-white">{{ $kos->kota }}</div>
                            <div class="text-xs text-slate-500">{{ $kos->kecamatan }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                @if($kos->rating > 0)
                                <div class="flex items-center text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($kos->rating))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $kos->rating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-sm font-bold">{{ number_format($kos->rating, 1) }}</span>
                                </div>
                                @else
                                <span class="text-sm text-slate-500">Belum ada rating</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                {{ $kos->status_kamar == 'Masih Tersedia' ? 'bg-green-900/30 text-green-300' : 
                                   ($kos->status_kamar == 'Hampir Penuh' ? 'bg-yellow-900/30 text-yellow-300' : 
                                   'bg-red-900/30 text-red-300') }}">
                                {{ $kos->status_kamar }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <button onclick="cekRatingKos({{ $kos->id_kos }}, '{{ $kos->nama_kos }}')"
                                    class="px-3 py-1 text-xs bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                                <i class="fas fa-star mr-1"></i>Lihat Rating
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kamar Tersedia per Kota -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4">Ringkasan Ketersediaan Kamar</h3>
        <p class="text-slate-400 mb-6">Jumlah kamar yang masih tersedia di setiap kota</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($kotaKamar as $kota => $jumlah)
            <div class="bg-slate-900/50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm text-slate-400">{{ $kota }}</div>
                    <i class="fas fa-map-marker-alt text-cyan-400"></i>
                </div>
                <div class="text-2xl font-bold text-white mb-1">{{ $jumlah }}</div>
                <div class="text-xs text-slate-500">kamar tersedia</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
        <a href="{{ route('public.home') }}" 
           class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
            <i class="fas fa-home"></i>
            <span>Kembali ke Beranda</span>
        </a>
        <a href="{{ route('public.procedure.ringkasan') }}" 
           class="px-6 py-3 bg-slate-800 border border-slate-700 text-white font-semibold rounded-xl hover:border-blue-500 hover:text-blue-300 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-chart-bar"></i>
            <span>Lihat Statistik</span>
        </a>
    </div>
</div>

<script>
    function cekRating() {
        const form = document.getElementById('formCekRating');
        const formData = new FormData(form);
        
        fetch("{{ route('public.function.cek-rating') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const hasilDiv = document.getElementById('hasilRating');
                const ratingValue = document.getElementById('ratingValue');
                const ratingStars = document.getElementById('ratingStars');
                
                ratingValue.textContent = parseFloat(data.rating).toFixed(1);
                
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= Math.floor(data.rating)) {
                        starsHtml += '<i class="fas fa-star"></i>';
                    } else if (i - 0.5 <= data.rating) {
                        starsHtml += '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        starsHtml += '<i class="far fa-star"></i>';
                    }
                }
                ratingStars.innerHTML = starsHtml;
                
                hasilDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghitung rating: ' + error.message);
        });
    }
    
    function cekKota() {
        const form = document.getElementById('formCekKota');
        const formData = new FormData(form);
        
        fetch("{{ route('public.function.cek-kota') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const hasilDiv = document.getElementById('hasilKota');
                const kotaLabel = document.getElementById('kotaLabel');
                const jumlahKamar = document.getElementById('jumlahKamar');
                
                kotaLabel.textContent = data.kota;
                jumlahKamar.textContent = data.jumlah_kamar_tersedia;
                
                hasilDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengecek ketersediaan: ' + error.message);
        });
    }
    
    function cekRatingKos(idKos, namaKos) {
        document.querySelector('select[name="id_kos"]').value = idKos;
        cekRating();
    }
</script>
@endsection