@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Kelola Bisnis')
@section('admin')
    <x-ui.bg-pink class="hidden md:block" />
    <div
        class="md:-mt-16 relative mb-9 overflow-hidden md:max-w-md md:mx-auto h-full bg-gray-100 rounded-2xl shadow-md flex items-center p-4">

        <img src="{{ asset('img/illustrations/toko.svg') }}" class="w-50 z-10 absolute bottom-0" alt="" srcset="">
        <x-right-motif class="md:scale-150" />

        <x-left-motif />

        <div class="pl-56 text-white pb-2 mr-auto z-10">
            <h2 class="text-2xl text-white font-bold ">{{ $business->name }}</h2>
            <p class="text-sm ">{{ $business->stocks_count }} Stok</p>
            <p class="text-sm ">{{ $business->categories_count }} Kategori</p>
            <p class="text-sm ">{{ $business->menus_count }} Menu</p>

        </div>
    </div>

    <div class="xl:flex xl:gap-4 w-full">
        <!-- TABEL BAHAN (lebih lebar) -->
        <div class="xl:w-3/4">
            <x-table :headers="[
                'Nama' => 'nama',
                'Harga' => 'harga_formatted',
                'Satuan' => 'satuan',
            ]" :rows="$stocks" :business_id="$business->id" label="TAMBAH" :total="$stocks->count()" title="Bahan"
                data-id="{{ $stocks->pluck('id') }}" :perPage="$perPage" :currentPage="$currentPage"
                buttonAction="togglePopup('popup-add')" :actions="['edit' => 'openEditStockPopup']" :showLabel="true" :button="true" />
        </div>

        <!-- TABEL KATEGORI (lebih kecil) -->
        <div class="xl:w-1/4">
            <x-table :headers="[
                'Nama Kategori' => 'nama',
            ]" :rows="$business->categories" title="Kategori" :business_id="$business->id"
                buttonAction="togglePopup('popup-add-kategori')" label="TAMBAH" :showLabel="true" :button="true"
                :actions="['edit' => 'openEditCategoryPopup']" />
        </div>
    </div>




    <x-modal-add id="popup-add" title="Tambah Bahan" :isEdit="false" action="{{ route('admin.stock.add') }}"
        method="POST" :inputs="[
            ['label' => 'Nama', 'name' => 'nama', 'type' => 'text', 'placeholder' => 'Nama Stok', 'required' => true],
            ['label' => 'Jumlah Stok', 'name' => 'jumlah_stok', 'type' => 'number', 'placeholder' => 'Jumlah Stok'],
            ['label' => 'Harga', 'name' => 'harga', 'type' => 'number', 'placeholder' => 'Harga'],
            [
                'label' => 'Satuan',
                'name' => 'satuan',
                'type' => 'text',
                'placeholder' => 'Contoh: pcs, kg, bungkus',
                'required' => true,
            ],
            ['label' => '', 'name' => 'business_id', 'type' => 'hidden', 'value' => $business->id],
        ]" />

    <x-modal-add id="popup-edit-stock" title="Edit Bahan" method="PUT" :isEdit="true" :inputs="[
        [
            'label' => 'Nama',
            'name' => 'nama',
            'type' => 'text',
            'placeholder' => 'Nama Stok',
            'required' => true,
        ],
    
        ['label' => 'Harga', 'name' => 'harga', 'type' => 'number', 'placeholder' => 'Harga'],
        [
            'label' => 'Satuan',
            'name' => 'satuan',
            'type' => 'text',
            'placeholder' => 'Contoh: pcs, kg, bungkus',
            'required' => true,
        ],
        ['label' => '', 'name' => 'stock_id', 'type' => 'hidden'],
    ]" />

    <x-modal-delete id="popup-edit-stock-delete" action="{{ route('admin.stock.destroy', ':id') }}" />

    {{-- <x-section-header title="Kategori" buttonAction="togglePopup('popup-add-kategori')" label="Tambah Kategori"
        :showLabel="true" /> --}}

    <x-modal-add id="popup-add-kategori" title="Tambah Kategori" :isEdit="false"
        action="{{ route('admin.category.add') }}" method="POST" :inputs="[
            [
                'label' => 'Nama',
                'name' => 'nama',
                'type' => 'text',
                'placeholder' => 'Nama Kategori',
                'required' => true,
            ],
            ['label' => '', 'name' => 'business_id', 'type' => 'hidden', 'value' => $business->id],
        ]" />




    <x-modal-add id="popup-edit-kategori" title="Edit Kategori" method="PUT" :isEdit="true" :inputs="[
        [
            'label' => 'Nama',
            'name' => 'nama',
            'type' => 'text',
            'placeholder' => 'Nama Kategori',
            'required' => true,
        ],
        ['label' => '', 'name' => 'business_id', 'type' => 'hidden', 'value' => $business->id],
    ]" />

    <x-modal-delete id="popup-edit-kategori-delete" action="{{ route('admin.kategori.destroy', ':id') }}" />

  

    <x-modal-add id="popup-add-menu" title="Tambah Menu" :isEdit="false" action="{{ route('admin.menu.add') }}"
        method="POST" :inputs="[
            ['label' => 'Nama', 'name' => 'nama', 'type' => 'text', 'placeholder' => 'Nama Menu', 'required' => true],
            ['label' => 'Foto', 'name' => 'foto', 'type' => 'file'],
            ['label' => 'Harga', 'name' => 'harga', 'type' => 'number', 'placeholder' => 'Harga'],
            [
                'label' => 'Kategori',
                'name' => 'kategori_id',
                'type' => 'select',
                'options' => $business->categories
                    ->map(function ($category) {
                        return ['value' => $category->id, 'label' => $category->nama];
                    })
                    ->toArray(),
                'required' => true,
            ],
            ['label' => '', 'name' => 'business_id', 'type' => 'hidden', 'value' => $business->id],
        ]" />

    <x-table :headers="[
        'Menu' => [
            'image' => 'foto',
            'title' => 'nama',
        ],
        'Harga' => 'harga',
        'Kategori' => 'category.nama',
    ]" :rows="$menus" title="Menu" :business_id="$business->id" :total="$totalMenus" :perPage="$perPage"
        :currentPage="$currentPage" buttonAction="togglePopup('popup-add-menu')" label="TAMBAH" :showLabel="true" :button="true"
        :actions="['edit' => 'openEditMenuPopup']" />

    <x-modal-add id="popup-edit-menu" title="Edit Menu" method="PUT" :isEdit="true" :inputs="[
        ['label' => 'Nama', 'name' => 'nama', 'type' => 'text', 'placeholder' => 'Nama Menu', 'required' => true],
        ['label' => 'Foto', 'name' => 'foto', 'type' => 'file'],
        ['label' => 'Harga', 'name' => 'harga', 'type' => 'number', 'placeholder' => 'Harga'],
        [
            'label' => 'Kategori',
            'name' => 'kategori_id',
            'type' => 'select',
            'options' => $business->categories
                ->map(function ($category) {
                    return ['value' => $category->id, 'label' => $category->nama];
                })
                ->toArray(),
            'required' => true,
        ],
        ['label' => '', 'name' => 'business_id', 'type' => 'hidden', 'value' => $business->id],
    ]" />

    <x-modal-delete id="popup-edit-menu-delete" action="{{ route('admin.menus.destroy', ':id') }}" />

    <script>
        function openEditStockPopup(button) {
            const stockId = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const harga = button.getAttribute('data-harga');
            const satuan = button.getAttribute('data-satuan');

            const modal = document.getElementById('popup-edit-stock');
            const form = modal.querySelector('form');

            const actionTemplate = "{{ route('admin.stock.update', ':id') }}";
            form.action = actionTemplate.replace(':id', stockId);

            form.querySelector('input[name="nama"]').value = nama ?? '';
            form.querySelector('input[name="harga"]').value = harga ?? '';
            form.querySelector('input[name="satuan"]').value = satuan ?? '';

            togglePopup('popup-edit-stock');

            const deleteModal = document.getElementById('popup-edit-stock-delete');
            const deleteForm = deleteModal.querySelector('form');
            const deleteTemplate = "{{ route('admin.stock.destroy', ':id') }}";
            deleteForm.action = deleteTemplate.replace(':id', stockId);
        }

        function openEditCategoryPopup(button) {
            const kategoriId = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const businessId = button.getAttribute('data-business_id');

            const modal = document.getElementById('popup-edit-kategori');
            const form = modal.querySelector('form');

            const actionTemplate = "{{ route('admin.kategori.update', ':id') }}";
            form.action = actionTemplate.replace(':id', kategoriId);

            form.querySelector('input[name="nama"]').value = nama ?? '';
            form.querySelector('input[name="business_id"]').value = businessId ?? '';

            togglePopup('popup-edit-kategori');

            const deleteModal = document.getElementById('popup-edit-kategori-delete');
            const deleteForm = deleteModal.querySelector('form');
            const deleteTemplate = "{{ route('admin.kategori.destroy', ':id') }}";
            deleteForm.action = deleteTemplate.replace(':id', kategoriId);
        }

        function openEditMenuPopup(button) {
            const menuId = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const harga = button.getAttribute('data-harga');
            const categoryId = button.getAttribute('data-category_id');
            const businessId = button.getAttribute('data-business_id');

            const modal = document.getElementById('popup-edit-menu');
            const form = modal.querySelector('form');

            const actionTemplate = "{{ route('admin.menus.update', ':id') }}";
            form.action = actionTemplate.replace(':id', menuId);

            form.querySelector('input[name="nama"]').value = nama ?? '';
            form.querySelector('input[name="harga"]').value = harga ?? '';
            form.querySelector('input[name="business_id"]').value = businessId ?? '';

            const categorySelect = form.querySelector('select[name="kategori_id"]');
            if (categorySelect) {
                categorySelect.value = categoryId ?? '';
            }

            togglePopup('popup-edit-menu');

            const deleteModal = document.getElementById('popup-edit-menu-delete');
            const deleteForm = deleteModal.querySelector('form');
            const deleteTemplate = "{{ route('admin.menus.destroy', ':id') }}";
            deleteForm.action = deleteTemplate.replace(':id', menuId);
        }
    </script>
@endsection
