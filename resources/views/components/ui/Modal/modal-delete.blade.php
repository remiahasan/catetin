@props([
    'id' => 'delete-modal',
    'action' => '#',
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="togglePopup('{{ $id }}')"></div>

    <div class="relative z-10 w-96 max-w-full px-3 bg-white rounded-2xl shadow-xl p-6 text-center">
        <h6 class="text-lg font-semibold text-gray-800 mb-3">Konfirmasi Hapus</h6>
        <p class="text-sm text-gray-600 mb-5">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak bisa
            dibatalkan.</p>

        <form method="POST" action="{{ $action }}">
            @csrf
            @method('DELETE')

            <div class="flex justify-center gap-3 mt-4">
                <button type="button" onclick="togglePopup('{{ $id }}')"
                    class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/modal/togglePopup.js') }}"></script>
