@props([
    'headers' => [],
    'rows' => [],
    'perPage' => 0,
    'currentPage' => 1,
    'total' => 0,
    'actions' => [],
    'business_id',
    'title',
    'label' => null,
    'buttonAction' => null,
    'showLabel' => false,
    'button' => false,
])

@php
    // Pagination aktif hanya jika perPage > 0
    $hasPagination = $perPage > 0;
@endphp
@if ($hasPagination)
    @php
        $perPage = $perPage > 0 ? $perPage : 0; // default 10 kalau 0/null
        $totalPages = $total > 0 ? ceil($total / $perPage) : 1;
        $currentPage = request()->get('page', 1);
        $start = ($currentPage - 1) * $perPage + 1;
        $end = min($start + $perPage - 1, $total);
    @endphp
@endif




<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">

            <x-section-header title="{{ $title }}" buttonAction="{{ $buttonAction }}"
                business_id="{{ $business_id }}" label="{{ $label }}" showLabel="{{ $showLabel }}"
                button="{{ $button }}" />




            <div class="{{ $hasPagination ? '' : ' h-[298px] overflow-y-auto' }}">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                @foreach ($headers as $label => $field)
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        {{ $label }}</th>
                                @endforeach

                                @if (!empty($actions))
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        edit</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $index => $row)
                                <tr>


                                    @foreach ($headers as $label => $column)
                                        {{-- Jika kolom berbentuk array → tampilkan gambar + title --}}
                                        @if (is_array($column))
                                            @php
                                                $imgValue = data_get($row, $column['image']);
                                                $titleValue = data_get($row, $column['title']);

                                                $imgPath =
                                                    $imgValue && file_exists(public_path($imgValue))
                                                        ? asset($imgValue)
                                                        : asset('img/illustrations/no-image.png');
                                            @endphp

                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">

                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <img src="{{ $imgPath }}"
                                                            class="mr-4 h-14 w-14 min-w-[56px] min-h-[56px] rounded-xl object-cover shadow"
                                                            alt="img" />
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold leading-normal">
                                                            {{ $titleValue }}</h6>
                                                    </div>
                                                </div>

                                            </td>
                                        @else
                                            <td
                                                class="p-2 px-6 align-middle bg-transparent border-t whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-sm  font-thin leading-tight text-slate-600">
                                                    {{ data_get($row, $column) }}
                                                </p>
                                            </td>
                                        @endif
                                    @endforeach

                                    @if (!empty($actions))
                                        <td
                                            class="p-2 px-6 bg-transparent border-t whitespace-nowrap shadow-transparent">
                                            @foreach ($actions as $label => $function)
                                                <button type="button" class="text-sm {{ $label }}-button"
                                                    onclick="{{ $function }}(this)">
                                                    {{ ucfirst($label) }}
                                                </button>
                                            @endforeach
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($headers) + (isset($actions) ? 2 : 1) }}"
                                        class="text-center text-sm py-4">
                                        Data tidak tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($hasPagination)
                        <nav class="flex items-center px-6 justify-between py-2" aria-label="Table navigation">
                            <span class="text-sm text-slate-500">
                                Menampilkan <span class="font-semibold">{{ $start }}</span>
                                - <span class="font-semibold">{{ $end }}</span>
                                dari <span class="font-semibold">{{ $total }}</span>
                            </span>

                            <ul class="inline-flex -space-x-px text-sm h-8">
                                <li>
                                    <a href="?page={{ max(1, $currentPage - 1) }}"
                                        class="px-3 h-8 flex items-center justify-center border rounded-l-lg bg-gradient-fuchsia {{ $currentPage == 1 ? 'text-gray-300 cursor-not-allowed' : 'hover:bg-gray-100 text-gray-500' }}">
                                        <svg class="w-6 h-6 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m15 19-7-7 7-7" />
                                        </svg>

                                    </a>
                                </li>

                                <li>
                                    <a href=""
                                        class="px-3 h-8 flex items-center justify-center border
                 text-slate-700">
                                        {{ $currentPage }}
                                    </a>
                                </li>

                                {{-- Next --}}
                                <li>
                                    <a href="?page={{ min($totalPages, $currentPage + 1) }}"
                                        class="px-3 h-8 flex items-center justify-center border rounded-e-lg bg-gradient-fuchsia-refresh {{ $currentPage == $totalPages ? 'text-gray-300 cursor-not-allowed' : 'hover:bg-gray-100 text-gray-500' }}">
                                        <svg class="w-6 h-6 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m9 5 7 7-7 7" />
                                        </svg>

                                    </a>
                                </li>
                            </ul>
                        </nav>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>


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
