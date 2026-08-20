@props([
    'stocks',
    'business' => null,
    'mode' => 'tambah', // nilai: tambah / update
    'alreadyUpdated' => false,
])

@php
    $isTambah = $mode === 'tambah';
    $formAction = $isTambah ? route('admin.stock.store.jumlah_stok') : route('pegawai.update.stock.store');
    $title = $isTambah ? 'Tambah Stok' : 'Update Sisa Jumlah Stok';
    $buttonText = $isTambah ? 'Tambah Semua' : 'Simpan Semua';
@endphp

<div class=" col-span-2 w-full max-w-full px-3">
    <div
        class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
        <div class=" px-6 flex my-9 items-center justify-between gap-2 mb-4">
            <h6>{{ $title }}</h6>
        </div>
        <form action="{{ $formAction }}" method="POST" id="{{ $isTambah ? 'form-tambah-stok' : 'form-update-stok' }}">
            @csrf
            @if ($business)
                <input type="hidden" name="business_id" value="{{ $business->id }}">
            @endif

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto overflow-y-auto max-h-80">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th
                                    class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Name</th>
                                <th
                                    class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Stok Saat Ini</th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    {{ $isTambah ? 'tambah stok' : 'sisa stok' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $key => $item)
                                <tr>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-4 py-1">
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal">{{ $item->nama }}</h6>
                                                <p class="mb-0 text-xs leading-tight text-slate-400">
                                                    {{ $item->satuan }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <p class="mb-0 text-xs leading-tight text-slate-400">
                                            {{ $item->jumlah_stok }}</p>
                                    </td>
                                    <td
                                        class="flex justify-center px-2 py-3 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        @if ($isTambah)
                                            <input type="hidden" name="stock[{{ $item->id }}][id]"
                                                value="{{ $item->id }}">
                                            <input type="number" name="stock[{{ $item->id }}][jumlah_tambah]"
                                                min="1"
                                                class="w-14 h-10 text-xs text-center border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                                                placeholder="0">
                                        @else
                                            <input type="number" name="jumlah_stok[{{ $item->id }}]"
                                                value="{{ $item->jumlah_stok }}" max="{{ $item->jumlah_stok }}"
                                                min="0" data-max="{{ $item->jumlah_stok }}"
                                                data-nama="{{ $item->nama }}"
                                                class="w-14 h-10 text-xs text-center border rounded-lg focus:outline-none focus:ring focus:border-blue-300" />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="py-4 flex justify-center">
                <button type="{{ $isTambah ? 'submit' : 'button' }}" id="{{ $isTambah ? '' : 'btn-save-stok' }}"
                    class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    {{ $buttonText }}
                </button>
            </div>
        </form>

        @if (!$isTambah)
            <x-modal-update-stok id="update-modal" :action="route('pegawai.update.stock.store')" />
            <div id="warning-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
                <div class="absolute inset-0 bg-black bg-opacity-40" onclick="togglePopup('warning-modal')">
                </div>
                <div class="relative z-10 w-96 max-w-full bg-white rounded-2xl shadow-xl p-6 text-center">
                    <h6 class="text-lg font-semibold text-red-600 mb-3">Peringatan</h6>
                    <p id="warning-text" class="text-sm text-gray-700 mb-5"></p>
                    <button onclick="togglePopup('warning-modal')"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        Tutup
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

