@extends('components.layout.PegawaiLayout.body.index')
@section('pegawai')

    <div class="px-4 md:px-8">
        {{-- Header --}}
        <div class="relative bg-white rounded-t-2xl w-full h-40 flex items-center justify-center overflow-hidden">
            <x-right-motif class="absolute w-40 top-20" />
            <div class="absolute backdrop-blur-3xl bg-purple-600/85 w-60 h-60 rounded-full -left-10"></div>
            <img src="{{ asset('img/illustrations/face2.svg') }}" alt="" class="absolute h-40 md:h-48">
            <h2 class="absolute bottom-4 md:top-5 md:left-5 font-bold text-white text-lg">Riwayat Transaksi</h2>
        </div>

        {{-- Tabel Transaksi --}}
        <div
            class="relative flex flex-col flex-auto min-w-0 drop-shadow-lg p-4 -mt-4 break-words border-0 rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-2">
                <p class="font-bold text-gray-800 text-sm">Kasir: {{ auth()->user()->name }}</p>
                <p class="font-bold text-gray-800 text-sm">{{ $transaksis->count() }} Transaksi</p>
            </div>
            <p class="text-gray-500 text-sm mb-3">Tanggal: {{ now()->translatedFormat('d M Y') }}</p>

            @if ($transaksis->isEmpty())
                <p class="text-center text-gray-500 py-6">Belum ada transaksi hari ini</p>
            @else
                <table class="w-full text-left text-sm border-t">
                    <thead>
                        <tr class="text-gray-600 border-b">
                            <th class="py-2">Waktu</th>
                            <th class="py-2">Jumlah Item</th>
                            <th class="py-2">Total Harga</th>
                            <th class="py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksis as $transaksi)
                            @php
                                $details = json_decode($transaksi->details, true);
                                $jumlahItem = collect($details)->sum('jumlah');
                            @endphp
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ $transaksi->created_at->format('H:i') }}</td>
                                <td class="py-3">{{ $jumlahItem }}</td>
                                <td class="py-3">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                                <td class="py-3 text-center">
                                    <button class="text-blue-600 px-2 py-1 rounded"
                                        onclick='openModal(
                                            "{{ $transaksi->business->name ?? 'Usaha' }}",
                                            "{{ $transaksi->user->name ?? 'Kasir' }}",
                                            "{{ $transaksi->created_at->translatedFormat('d M Y H:i') }}",
                                            @json($transaksi->details),
                                            "{{ number_format($transaksi->total_bayar, 0, ',', '.') }}",
                                            "{{ number_format($transaksi->uang_dibayarkan, 0, ',', '.') }}",
                                            "{{ number_format($transaksi->kembalian, 0, ',', '.') }}"
                                        )'>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            @if ($transaksis->isNotEmpty())
                <div class="mt-4 border-t pt-3 text-right">
                    <p class="font-bold text-gray-800 text-lg">
                        Total Pendapatan:
                        <span class="text-green-600">
                            Rp.{{ number_format($totalHariIni, 0, ',', '.') }}
                        </span>
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Detail Transaksi -->
    <div id="modalDetail" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center"
        onclick="outsideClose(event)">
        <div class="bg-white rounded-xl mx-7 p-4 w-full max-w-md relative" onclick="event.stopPropagation()">
            <!-- Tombol Close -->
            <button onclick="closeModal()"
                class="absolute top-2 right-3 text-gray-400 hover:text-gray-600 text-xl font-bold">
                ×
            </button>

            <h3 class="text-lg font-bold mb-2 border-b text-center">Detail Transaksi</h3>

            <div class="text-xs mb-3 border-b pb-3 space-y-1">
                <div class="flex justify-between">
                    <span>Jenis Usaha</span><span id="modal-jenis-usaha"></span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir</span><span id="modal-kasir"></span>
                </div>
                <div class="flex justify-between">
                    <span>Waktu</span><span id="modal-waktu"></span>
                </div>
            </div>

            <div id="modal-items" class="space-y-1 mb-3 border-b pb-3"></div>

            <div class="text-sm space-y-1">
                <div class="flex justify-between font-bold"><span>Total:</span><span id="modal-total"></span></div>
                <div class="flex justify-between font-bold"><span>Bayar:</span><span id="modal-bayar"></span></div>
                <div class="flex justify-between font-bold"><span>Kembali:</span><span id="modal-kembali"></span></div>
            </div>
        </div>
    </div>

    <script>
        function openModal(jenisUsaha, kasir, waktu, items, total, bayar, kembali) {
            if (typeof items === 'string') {
                try {
                    items = JSON.parse(items);
                } catch {
                    items = [];
                }
            }

            document.getElementById('modal-jenis-usaha').textContent = jenisUsaha;
            document.getElementById('modal-kasir').textContent = kasir;
            document.getElementById('modal-waktu').textContent = waktu;
            document.getElementById('modal-total').textContent = 'Rp ' + total;
            document.getElementById('modal-bayar').textContent = 'Rp ' + bayar;
            document.getElementById('modal-kembali').textContent = 'Rp ' + kembali;

            const container = document.getElementById('modal-items');
            container.innerHTML = '';
            items.forEach(it => {
                const div = document.createElement('div');
                div.className = 'flex justify-between text-sm';
                div.innerHTML =
                    `<span>${it.jumlah} x ${it.nama}</span><span>Rp ${Number(it.harga).toLocaleString('id-ID')}</span>`;
                container.appendChild(div);
            });

            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        function outsideClose(e) {
            if (e.target.id === 'modalDetail') closeModal();
        }
    </script>


@endsection
