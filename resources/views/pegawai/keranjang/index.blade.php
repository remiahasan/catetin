@extends('components.layout.PegawaiLayout.body.index')
@section('pegawai')
    <div class="container mx-auto space-y-4">
        {{-- Judul --}}
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold">Keranjang Belanja</h2>
            </div>
        </div>

        {{-- List Keranjang --}}
        <div class="space-y-4 overflow-y-auto pb-56 hide-scrollbar">
            @if ($keranjangs->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-gray-600">
                    <p class="text-lg font-bold">Belum ada menu yang ditambahkan</p>
                </div>
            @else
                @foreach ($keranjangs as $keranjang)
                    <div class="bg-white rounded-xl shadow p-3 flex gap-3 md:grid-cols-2" id="cart-item-{{ $keranjang->id }}">
                        @if ($keranjang->menu && $keranjang->menu->foto)
                            <img class="rounded-lg w-16 h-16 object-cover" src="{{ asset($keranjang->menu->foto) }}"
                                alt="" />
                        @else
                            <img class="rounded-lg w-16 h-16 object-cover"
                                src="{{ asset('img/illustrations/no-image.png') }}" alt="" />
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-semibold">
                                {{ $keranjang->menu ? $keranjang->menu->nama : 'Menu Tidak Ditemukan' }}
                            </p>
                            <div id="total-harga-{{ $keranjang->id }}" class="text-purple-700 font-semibold text-sm mt-1">
                                @php echo 'Rp ' . number_format($keranjang->total_harga, 0, ',', '.'); @endphp
                            </div>
                        </div>

                        {{-- Tombol hapus --}}
                        <button type="button" class="text-red-500 remove-item-btn" data-id="{{ $keranjang->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        {{-- Tombol jumlah --}}
                        <div class="flex flex-col items-center justify-between">
                            <button onclick="updateQuantity('{{ $keranjang->id }}', 'increment')"
                                class="bg-gray-200 w-6 h-6 flex items-center justify-center rounded">+</button>
                            <span id="jumlah-{{ $keranjang->id }}" class="text-sm">{{ $keranjang->jumlah }}</span>
                            <button onclick="updateQuantity('{{ $keranjang->id }}', 'decrement')"
                                class="bg-gray-200 w-6 h-6 flex items-center justify-center rounded">-</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="fixed bottom-0 left-0 right-0 bg-gray-50 p-4 z-10">
            {{-- Ringkasan --}}
            <div class="space-y-2">
                <!-- Kotak Total -->
                <div class="bg-white rounded-xl shadow p-4 flex justify-between items-center">
                    <span class="font-semibold">Total</span>
                    <span id="total-bayar" class="font-bold">
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Kotak Kembalian -->
                <div id="box-kembalian" class="bg-white rounded-xl shadow p-4 flex justify-between items-center hidden">
                    <span class="font-semibold text-green-600">Kembalian</span>
                    <span id="kembalian" class="font-bold text-green-600">Rp 0</span>
                </div>
            </div>

            {{-- Tombol Pesan --}}
            <form id="checkoutForm" action="{{ route('pegawai.keranjang.checkout') }}" method="POST">
                @csrf
                <div class="my-4">
                    <label for="uang_dibayarkan" class="block text-sm font-medium text-gray-700">
                        Jumlah Uang Dibayarkan
                    </label>
                    <input type="number" name="uang_dibayarkan" id="uang_dibayarkan" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <button type="submit"
                        class="w-full text-white bg-gradient-fuchsia hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 text-base font-semibold py-3 rounded-full">
                        Bayar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- script lama tetap jalan --}}
    <script>
        function updateQuantity(keranjangId, action) {
            fetch(`/pegawai/keranjang/update-quantity/${keranjangId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('jumlah-' + keranjangId).textContent = data.jumlah_baru;
                        document.getElementById('total-harga-' + keranjangId).textContent = data.total_harga_formatted;
                        document.getElementById('total-bayar').textContent = data.total_bayar_formatted;
                    } else {
                        alert('Gagal update keranjang.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.getElementById('checkoutForm').addEventListener('submit', function(event) {
            const totalText = document.getElementById('total-bayar').textContent.trim();
            const totalValue = parseInt(totalText.replace(/[^\d]/g, ''));
            const uangDibayarkan = parseInt(document.getElementById('uang_dibayarkan').value);

            if (uangDibayarkan < totalValue) {
                event.preventDefault();
                showPopup('Uang yang dibayarkan kurang dari total pembayaran!', 'error');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const removeButtons = document.querySelectorAll('.remove-item-btn');

            removeButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const keranjangId = this.dataset.id;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');
                    const url = `/pegawai/keranjang/${keranjangId}`;

                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            try {
                                return await response.json();
                            } catch {
                                throw new Error('Respons server tidak valid.');
                            }
                        })
                        .then(data => {
                            if (data.success) {
                                const cartItemElement = document.getElementById('cart-item-' +
                                    keranjangId);
                                if (cartItemElement) {
                                    cartItemElement.style.transition = 'opacity 0.3s ease';
                                    cartItemElement.style.opacity = '0';
                                    setTimeout(() => cartItemElement.remove(), 300);
                                }
                                const totalBayarElement = document.getElementById(
                                    'total-bayar');
                                if (totalBayarElement) {
                                    totalBayarElement.textContent = data.total_bayar_formatted;
                                }
                                showPopup(data.message, 'success');
                            } else {
                                showPopup('Gagal menghapus item: ' + (data.message ||
                                    'Terjadi kesalahan.'), 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showPopup(
                                'Terjadi kesalahan jaringan atau server saat menghapus item.',
                                'error');
                        });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const uangInput = document.getElementById('uang_dibayarkan');
            const kembalianEl = document.getElementById('kembalian');
            const boxKembalian = document.getElementById('box-kembalian');

            const formatRupiah = (angka) => {
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            };

            const hitungKembalian = () => {
                const totalText = document.getElementById('total-bayar').textContent.trim();
                const totalValue = parseInt(totalText.replace(/[^\d]/g, '')) || 0;
                const uangDibayarkan = parseInt(uangInput.value) || 0;
                const kembalian = uangDibayarkan - totalValue;

                if (uangDibayarkan > 0) {
                    boxKembalian.classList.remove('hidden');
                    kembalianEl.textContent = formatRupiah(kembalian > 0 ? kembalian : 0);
                } else {
                    boxKembalian.classList.add('hidden');
                }
            };

            // Perhitungan ulang setiap user input uang
            uangInput.addEventListener('input', hitungKembalian);

            // 👉 Perhitungan ulang juga saat total berubah (quantity tambah, kurang, hapus)
            const observer = new MutationObserver(hitungKembalian);
            observer.observe(document.getElementById('total-bayar'), {
                childList: true
            });
        });
    </script>
@endsection
