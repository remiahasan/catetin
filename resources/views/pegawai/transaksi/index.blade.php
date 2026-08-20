@extends('components.layout.PegawaiLayout.body.index')
@section('pegawai')
    <div class="flex flex-col md:flex-row justify-between">
        <h1 class="font-bold text-lg px-4 py-2">Menu Transaksi</h1>
        <!-- Search -->
        <form id="search-input" placeholder="Cari Produk"
            class="w-full md:max-w-md px-4 pb-4 md:pb-0 mx-auto md:ml-auto md:mr-0">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="default-search"
                    class="block w-full py-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-3xl bg-gray-50 "
                    placeholder="Cari Menu.." required />
                <button type="submit"
                    class="text-white  absolute end-2.5 bottom-2.5 bg-gradient-orange hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-3xl text-sm px-4 py-1">Cari</button>
            </div>
        </form>
    </div>

    <!-- CATEGORY FILTER + VIEW MODE -->
    <div class="relative flex items-center justify-between px-4 py-2 md:pb-2 w-full text-sm z-50">
        <!-- Kategori -->
        <div class="flex gap-3 overflow-x-auto flex-1 pr-3" id="kategori-buttons">
            {{-- Tombol ALL --}}
            <button onclick="loadMenus('all', this)" class="kategori-btn px-3 py-1 rounded-full bg-pink-500 text-white">
                All
            </button>

            {{-- Tombol Kategori Dinamis --}}
            @foreach ($categories as $category)
                <button onclick="loadMenus('{{ $category->id }}', this)"
                    class="kategori-btn px-3 py-1 rounded-full bg-gray-200 text-gray-700 whitespace-nowrap">
                    {{ $category->nama }}
                </button>
            @endforeach
        </div>

        <!-- Dropdown Tampilan -->
        <div class="absolute right-4 md:static flex-shrink-0 z-50">
            <label for="view-mode" class="text-gray-600 text-sm mr-2 hidden md:inline">Tampilan:</label>
            <select id="view-mode" onchange="setViewMode(this.value)"
                class="border border-gray-300 bg-white rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-pink-400 focus:outline-none cursor-pointer">
                <option value="card">Card</option>
                <option value="list">List</option>
            </select>
        </div>
    </div>



    <!-- LIST MENU -->
    <div id="menu-list" class="max-w-7xl xl:max-w-full mx-auto p-4 grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-4">
        <!-- Menu akan di-load dengan AJAX -->
    </div>

    {{-- tombol keranjang --}}
    <div id="floating-cart"
        class="fixed ring-2 ring-white bottom-10 right-4 w-14 h-14 rounded-full bg-gradient-to-tl from-purple-700 to-pink-500 shadow-lg flex items-center justify-center z-50">
        <a href="{{ route('pegawai.keranjang') }}" class="flex items-center justify-center w-full h-full relative">
            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.605l.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z"
                    clip-rule="evenodd" />
            </svg>

            <!-- Badge jumlah item -->
            <span id="cart-badge"
                class="absolute top-0 right-0 w-5 h-5 text-xs text-white bg-red-500 rounded-full flex items-center justify-center hidden">
                0
            </span>
        </a>
    </div>

    <!-- Reusable Modal Notification -->
    <div id="globalModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full mx-4 p-6 text-center transform transition-all scale-95">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-2">Pemberitahuan</h3>
            <p id="modalMessage" class="text-gray-600 mb-4">Pesan notifikasi di sini</p>
            <button id="modalCloseBtn"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold py-2 rounded-lg hover:opacity-90">
                Tutup
            </button>
        </div>
    </div>

    <script src="{{ asset('js/loadMenu.js') }}"></script>
    <script>
        // 🔹 Notifikasi stok berhasil diperbarui
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showPopup("{{ session('success') }}", 'success');
            });
        @endif

        // 🔹 Notifikasi error stok
        @if (session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showPopup("{{ session('error') }}", 'error');
            });
        @endif
    </script>
    <script>
        // Fungsi universal untuk menampilkan modal
        function showModal(message, title = "Pemberitahuan") {
            const modal = document.getElementById('globalModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalCloseBtn = document.getElementById('modalCloseBtn');

            modalTitle.textContent = title;
            modalMessage.textContent = message;

            // Tampilkan modal
            modal.classList.remove('hidden');
            setTimeout(() => modal.classList.add('scale-100'), 10);

            // Tutup modal
            modalCloseBtn.onclick = () => {
                modal.classList.add('hidden');
                modal.classList.remove('scale-100');
            };

            // Klik di luar modal untuk menutup
            modal.onclick = (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('scale-100');
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            const kembalian = {{ session('kembalian') }};
            showModal('Kembalian Anda: Rp ' + new Intl.NumberFormat('id-ID').format(kembalian),
                'Pembayaran Berhasil');
        });
    </script>
@endsection
