@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Laporan')
@section('admin')

    <div class="flex flex-col space-y-6 md:flex-row gap-6 h-auto md:h-[calc(100vh-100px)] justify-evenly">
        <!-- Kolom Kiri: Kalender (ukuran lebih besar) -->
        <div class="md:w-1/3 xl:w-1/3 w-full flex justify-center md:items-start md:sticky top-0">
            <div id="datepicker-inline"
                class="w-full md:w-[420px] md:h-[calc(100vh-80px)] p-4 flex justify-center md:justify-center scale-[1.15] origin-top"
                style="
            [inline-datepicker] table {
                font-size: 1.5rem; /* perbesar teks tanggal */
            }
            [inline-datepicker] th,
            [inline-datepicker] td {
                padding: 1rem; /* perbesar kotak tanggal */
            }
        "
                inline-datepicker data-date="{{ \Carbon\Carbon::parse($tanggal)->format('m/d/Y') }}"
                data-max-date="{{ now()->format('m/d/Y') }}" data-date-format="mm/dd/yyyy"
                datepicker-max-date="{{ now()->format('m/d/Y') }}">
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Usaha dengan Scroll -->
        <div class="md:w-1/2 xl:w-1/2 overflow-y-auto md:h-[calc(100vh-60px)] max-h-[600px] md:max-h-none pr-2 space-y-4">
            @foreach ($business as $usaha)
                <div class="relative flex items-center justify-between bg-white rounded-2xl shadow mb-2 overflow-hidden">
                    <x-left-motif class="absolute left-0 top-0 h-full" />

                    <div class="z-10 flex-1 px-6 py-4">
                        <h2 class="font-bold text-2xl">{{ $usaha->name }}</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $usaha->lokasi ?? 'Lokasi tidak tersedia' }}
                        </p>
                    </div>

                    <div class="z-10 py-4 mx-4 rounded-l-full text-right">
                        <p class="text-sm mb-2 text-white">
                            Pendapatan: Rp {{ number_format($usaha->transaksis->sum('total_bayar') ?? 0, 0, ',', '.') }}
                        </p>
                        <div class="flex space-x-3 justify-end">
                            <button onclick="openModal('{{ $usaha->id }}')"
                                class="bg-white px-4 py-1 rounded-full shadow text-sm font-medium {{ \Carbon\Carbon::parse($tanggal)->lt(now()->startOfDay()) ? ' text-slate-200 cursor-not-allowed' : '' }}"
                                {{ \Carbon\Carbon::parse($tanggal)->lt(now()->startOfDay()) ? 'disabled' : '' }}>
                                Masukkan Stok
                            </button>

                            <a href="{{ route('admin.laporan.detailLaporan', $usaha->id) }}?date={{ $tanggal }}"
                                class="bg-white px-4 py-1 rounded-full shadow text-sm font-medium">
                                Lihat
                            </a>
                        </div>
                    </div>

                    <x-right-motif class="absolute h-60 top-0 w-max" />
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal input stok -->
    <div id="stokModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-11/12 max-w-2xl p-6">
            <h2 class="text-center text-lg font-bold mb-4">Masukkan Stok</h2>

            <form action="{{ route('admin.stock.store.jumlah_stok') }}" method="POST">
                @csrf
                <input type="hidden" id="business_id_modal" name="business_id" value="{{ old('business_id') }}">

                <!-- stok akan dimasukkan via JS -->
                <div class="space-y-3 max-h-80 overflow-y-auto" id="stokList">
                    <!-- JS akan inject stok disini -->
                </div>

                <!-- Tombol Bawah -->
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="closeModal()"
                        class="w-1/2 mr-2 py-2 bg-gray-200 rounded-xl text-gray-700 font-medium">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 ml-2 py-2 bg-blue-500 text-white rounded-xl font-medium">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let picker = document.getElementById("datepicker-inline");

            picker.addEventListener("changeDate", function(e) {
                console.log("event changeDate:", e.detail);

                if (!e.detail || !e.detail.date) return;

                let d = e.detail.date;
                let year = d.getFullYear();
                let month = String(d.getMonth() + 1).padStart(2, '0');
                let day = String(d.getDate()).padStart(2, '0');
                let dateStr = `${year}-${month}-${day}`;

                console.log("Tanggal dipilih:", dateStr);

                // reload halaman dengan ?date=YYYY-MM-DD
                window.location.href = `/admin/laporan?date=${dateStr}`;
            });
        });

        function openModal(businessId) {
            document.getElementById('business_id_modal').value = businessId;

            // kosongkan daftar stok
            const stokList = document.getElementById('stokList');
            stokList.innerHTML = '<p class="text-center">Loading...</p>';

            // tampilkan modal
            const modal = document.getElementById('stokModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // ambil data stok via ajax
            fetch(`/admin/business/${businessId}/stocks`)
                .then(res => res.json())
                .then(data => {
                    stokList.innerHTML = '';
                    data.forEach(item => {
                        stokList.innerHTML += `
                    <div class="border border-gray-300 rounded-lg bg-gray-50 shadow p-4">
                        <label for="jumlah_stok_${item.id}"
                            class="block text-md font-semibold text-gray-700 mb-2">${item.nama}</label>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="decreaseValue('${item.id}')"
                                class="px-3 py-1 bg-gray-300 rounded-l-xl text-lg font-bold">-</button>
                            <input id="jumlah_stok_${item.id}" type="number"
                                name="jumlah_stok[${item.id}]" value="${item.jumlah_stok ?? 0}"" placeholder="Jumlah"
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <span class="text-gray-600 font-medium">${item.satuan ?? ''}</span>
                            <button type="button" onclick="increaseValue('${item.id}')"
                                class="px-3 py-1 bg-gray-300 rounded-r-xl text-lg font-bold">+</button>
                        </div>
                    </div>`;
                    });
                });
        }

        function closeModal() {
            const modal = document.getElementById('stokModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // klik luar modal menutup
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('stokModal');
            if (e.target === modal) {
                closeModal();
            }
        });

        // fungsi tambah/kurang
        function increaseValue(id) {
            let input = document.getElementById('jumlah_stok_' + id);
            input.value = parseInt(input.value || 0) + 1;
        }

        function decreaseValue(id) {
            let input = document.getElementById('jumlah_stok_' + id);
            let current = parseInt(input.value || 0);
            if (current > 0) input.value = current - 1;
        }
    </script>

@endsection
