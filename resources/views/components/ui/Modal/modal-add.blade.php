@props([
    'id' => 'default-modal',
    'title' => 'Default Title',
    'action' => '#',
    'method' => 'POST',
    'inputs' => [],
    'deleteAction' => '#',
    'isEdit' => '#',
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="togglePopup('{{ $id }}')"></div>

    {{-- Modal content --}}
    <div class="relative z-10 w-96 max-w-full px-3 bg-white rounded-2xl shadow-xl">
        <div class="flex items-center justify-between p-4 border-b">
            @if ($isEdit)
                {{-- Mode Edit: Judul kiri + ikon hapus --}}
                <h6 class="text-lg font-semibold">{{ $title }}</h6>
                @if ($deleteAction)
                    <button type="button" onclick="openDeleteModal('{{ $id }}-delete')"
                        class="text-red-600 hover:text-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            aria-hidden="true" role="img">
                            <title>Hapus</title>
                            <path fill="#EF4444"
                                d="M9 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1h4a1 1 0 1 1 0 2h-1.092l-1.27 14.338A3 3 0 0 1 13.65 22H10.35a3 3 0 0 1-2.988-2.662L6.092 5H5a1 1 0 1 1 0-2h4Zm2.5 6a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0v-6a1 1 0 0 0-1-1ZM9 6h6v1H9V6Z" />
                        </svg>
                    </button>
                @endif
            @else
                {{-- Mode Tambah: Judul center --}}
                <h6 class="text-lg font-semibold text-center w-full">{{ $title }}</h6>
            @endif
        </div>

        <div class="flex-auto p-4">
            <form action="{{ $action }}" method="{{ strtolower($method) === 'get' ? 'GET' : 'POST' }}"
                enctype="multipart/form-data" class="max-w-sm pt-2 mx-auto">
                @csrf
                @if (!in_array(strtoupper($method), ['GET', 'POST']))
                    @method($method)
                @endif

                @foreach ($inputs as $input)
                    <div class="mb-4">
                        @if (($input['type'] ?? 'text') !== 'hidden')
                            <label for="{{ $input['name'] }}" class="block mb-1 text-sm">
                                {{ $input['label'] ?? ucfirst($input['name']) }}
                            </label>
                        @endif

                        @php
                            $inputName = $input['name'];
                            $inputType = $input['type'] ?? 'text';
                            $inputValue = old($inputName, $input['value'] ?? '');
                        @endphp

                        @if ($inputType === 'select')
                            <select name="{{ $inputName }}" id="{{ $inputName }}"
                                class="w-full border rounded-lg p-2"
                                {{ !empty($input['required']) ? 'required' : '' }}>
                                <option value="">-- Pilih {{ $input['label'] ?? ucfirst($inputName) }} --
                                </option>
                                @foreach ($input['options'] ?? [] as $option)
                                    <option value="{{ $option['value'] }}"
                                        {{ $inputValue == $option['value'] ? 'selected' : '' }}>
                                        {{ $option['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif ($inputType === 'hidden')
                            <input type="hidden" name="{{ $inputName }}" value="{{ $inputValue }}">
                        @else
                            <input type="{{ $inputType }}" name="{{ $inputName }}" id="{{ $inputName }}"
                                class="w-full border rounded-lg p-2" placeholder="{{ $input['placeholder'] ?? '' }}"
                                value="{{ $inputValue }}" {{ !empty($input['required']) ? 'required' : '' }}>
                        @endif
                    </div>
                @endforeach

                {{ $slot }}

                <div class="flex justify-center gap-2 mt-4">
                    <button type="submit"
                        class="text-white w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/modal/togglePopup.js') }}"></script>
