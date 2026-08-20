@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Kelola Bisnis')
@section('admin')

    {{-- Searching --}}
    <div class="flex justify-between items-center mb-6">
        {{-- Search kiri --}}
        <form action="{{ route('admin.kelola-bisnis') }}" method="GET"
            class="rounded-md mt-4 focus:outline-none focus:ring-2 w-full md:w-80">
            <div class="relative mr-2">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="default-search" name="search"
                    class="block w-full py-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-3xl bg-gray-50"
                    placeholder="Cari Bisnis atau Lokasi.." value="{{ request('search') }}" />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-gradient-orange hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-3xl text-sm px-4 py-1">
                    Cari
                </button>
            </div>
        </form>

        {{-- Tombol Tambah kanan --}}
        <x-plus-button buttonAction="togglePopup('popup-add-bisnis')" label="Tambah Usaha" :showLabel="true" />
    </div>


    {{-- Modal Tambah --}}
    <x-modal-add id="popup-add-bisnis" title="Tambah Bisnis" :isEdit="false"
        action="{{ route('admin.kelola-bisnis.add') }}" method="POST" :inputs="[
            ['label' => 'Nama', 'name' => 'name', 'type' => 'text', 'placeholder' => 'Nama Bisnis', 'required' => true],
            [
                'label' => 'Lokasi',
                'name' => 'lokasi',
                'type' => 'text',
                'placeholder' => 'Nama Lokasi',
                'required' => true,
            ],
        ]" />

    {{-- Daftar Bisnis --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach ($business as $bisnis)
            <div
                class="relative overflow-hidden max-w-sm md:max-w-md lg:max-w-lg h-full mr-1 bg-gray-100 rounded-2xl shadow-md flex items-center">

                {{-- Motif latar belakang --}}
                <x-right-motif class="z-0" />
                <x-left-motif class="z-0" />

                {{-- Gambar toko --}}
                <img src="{{ asset('img/illustrations/toko.svg') }}" class="absolute -bottom-1 left-0 w-40 sm:w-48 z-10"
                    alt="">

                <!-- Tombol Edit -->
                <button data-id="{{ $bisnis->id }}" data-nama="{{ $bisnis->name }}" data-lokasi="{{ $bisnis->lokasi }}"
                    onclick="openEditBusinessPopup(this)"
                    class="absolute text-sm flex items-center z-30 text-white top-2 right-0 py-1 pr-1 pl-2 bg-gradient-orange rounded-s-20">
                    <p>Edit</p> <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1"
                            d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                    </svg>
                </button>

                {{-- Konten kanan --}}
                <div class="relative flex flex-col items-end justify-center w-full text-right p-4 z-20">

                    {{-- Informasi bisnis di kanan --}}
                    <div class="pr-14 mt-4 flex flex-col items-start">
                        <h2 class="text-2xl font-bold text-white">{{ $bisnis->name }}</h2>
                        <p class="text-sm text-white">{{ $bisnis->users_count }} Pegawai</p>
                        <p class="text-sm text-white">{{ $bisnis->menus_count }} Menu</p>
                        <x-button-inside onclick="window.location='{{ route('admin.kelola-bisnis.kelola', $bisnis->id) }}'"
                            class="mt-4 bg-white font-semibold text-blue-600">
                            Kelola
                        </x-button-inside>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <x-modal-add id="popup-edit-business" title="Edit Bisnis" method="PUT" :isEdit="true" :inputs="[
        ['label' => 'Nama', 'name' => 'name', 'type' => 'text', 'placeholder' => 'Nama Bisnis', 'required' => true],
        ['label' => 'Lokasi', 'name' => 'lokasi', 'type' => 'text', 'placeholder' => 'Nama Lokasi', 'required' => true],
    ]" />

    <x-modal-delete id="popup-edit-business-delete" action="{{ route('admin.kelola-bisnis.destroy', ':id') }}" />

@endsection

<script>
    function openEditBusinessPopup(button) {
        const businessId = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const lokasi = button.getAttribute('data-lokasi');

        const modal = document.getElementById('popup-edit-business');
        const form = modal.querySelector('form');

        const actionTemplate = "{{ route('admin.kelola-bisnis.update', ':id') }}";
        form.action = actionTemplate.replace(':id', businessId);

        form.querySelector('input[name="name"]').value = nama ?? '';
        form.querySelector('input[name="lokasi"]').value = lokasi ?? '';

        togglePopup('popup-edit-business');

        const deleteModal = document.getElementById('popup-edit-business-delete');
        const deleteForm = deleteModal.querySelector('form');
        const deleteTemplate = "{{ route('admin.kelola-bisnis.destroy', ':id') }}";
        deleteForm.action = deleteTemplate.replace(':id', businessId);
    }
</script>
