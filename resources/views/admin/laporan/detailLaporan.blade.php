@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Detail Laporan')
@section('admin')

    {{-- Header --}}
    {{-- <x-ui.bg-pink class="hidden md:block" /> --}}
    <div class=" flex flex-wrap xl:flex-nowrap md:items-center md:justify-center ">
        <div class="md:flex-none">
            <!-- FILTER TANGGAL -->
            <form method="GET" action="{{ route('admin.laporan', $business->id) }}"
                class="flex md:block items-center md:items-center justify-center md:justify-center space-x-4 p-4 rounded-xl text-center mx-auto">
                <div id="date-range-picker" date-rangepicker class="flex md:block justify-center items-center mx-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="datepicker-range-start" name="start" type="text" value="{{ request('start') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full ps-10 p-2.5"
                            placeholder="Tanggal Awal">
                    </div>
                    <span class="mx-4 items-center text-gray-500">to</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="datepicker-range-end" name="end" type="text" value="{{ request('end') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full ps-10 p-2.5"
                            placeholder="Tanggal Akhir">
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="flex md:mt-2 bg-gradient-fuchsia text-white items-center gap-2 px-4 py-3 rounded-lg text-xs font-bold hover:bg-gray-50 transition mx-auto">
                        TAMPILKAN
                    </button>
                </div>
            </form>
        </div>

        <!-- KARTU TOKO -->
        <div class="md:flex-none">
            <div class="relative overflow-hidden max-w-96 h-40 bg-gray-100 rounded-2xl shadow-md flex items-center px-4">
                <img src="{{ asset('img/illustrations/toko.svg') }}" class="w-40 z-10 absolute bottom-0" alt="">
                <x-right-motif />
                <x-left-motif />
                <div class="pl-56 text-white pb-2 mr-auto z-10">
                    <h2 class="text-3xl font-bold text-white">{{ $business->name }}</h2>
                    <p class="text-sm">Pendapatan Pada</p>
                    <p class="text-sm">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>
                    <p class="text-2xl font-bold">
                        Rp {{ number_format($business->transaksis->sum('total_bayar'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl w-full md:h-40 shadow-md mt-4 md:mt-0 xl:ml-4 px-3 pt-4">
            {{-- Pegawai --}}
            <h3 class="text-lg font-bold mb-2">Pegawai</h3>

            <!-- Tambahkan wrapper scroll -->
            <div class="flex flex-col md:max-h-24 md:overflow-y-auto">
                @forelse ($business->users ?? [] as $pegawai)
                    @php
                        $jumlahTransaksi = $business->transaksis->where('user_id', $pegawai->id)->count();
                    @endphp

                    <a href="{{ route('admin.laporan.pegawai', $pegawai->id) }}?date={{ $tanggal }}">
                        <div class="flex items-center justify-between py-2 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $pegawai->photo ? asset($pegawai->photo) : asset('img/illustrations/face2.svg') }}"
                                    class="w-12 h-12 object-cover rounded-full" alt="">
                                <div>
                                    <p class="font-bold">{{ $pegawai->name }}</p>
                                    <p class="text-xs text-gray-500">Transaksi: {{ $jumlahTransaksi }}</p>
                                </div>
                            </div>

                            <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m9 5 7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                @empty
                    <p class="text-gray-500 text-sm">Tidak ada pegawai</p>
                @endforelse
            </div>
            <!-- End scroll -->
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 we-full gap-4 mt-4 md:mt-6">
        {{-- Stok --}}
        <x-table :headers="[
            'Nama Stok' => 'stocks.nama',
            'Stok Awal' => 'stok_awal',
            'Stok Akhir' => 'stok_akhir',
            'Stok Keluar' => 'stok_keluar',
        ]" :rows="$stocks" title="Stok" :business_id="$business->id" :total="$total" :perPage="$perPage"
            :currentPage="$currentPage" />

        {{-- Terjual --}}
        <x-table :headers="[
            'Nama Menu' => 'nama',
            'Jumlah Terjual' => 'jumlah',
            'Harga Satuan' => 'harga',
            'Total' => 'total',
        ]" :rows="$allItems" title="Terjual" :business_id="$business->id" :total="0" :perPage="null"
            :currentPage="null" />

    </div>


@endsection
