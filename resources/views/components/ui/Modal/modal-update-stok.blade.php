@props([
    'id' => 'update-modal',
    'action' => '#',
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="togglePopup('{{ $id }}')"></div>

    <div class="relative z-10 w-96 max-w-full px-3 bg-white rounded-2xl shadow-xl p-6 text-center">
        <h6 class="text-lg font-semibold text-gray-800 mb-3">Konfirmasi Update Stok</h6>
        <p class="text-sm text-gray-600 mb-5">
            Apakah Anda yakin ingin menyimpan perubahan jumlah stok? Tindakan ini tidak bisa dibatalkan.
        </p>

        <form id="update-stok-form" method="POST" action="{{ $action }}">
            @csrf

            <div class="flex justify-center gap-3 mt-4">
                <button type="button" onclick="togglePopup('{{ $id }}')"
                    class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Ya, Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePopup(id) {
        const popup = document.getElementById(id);
        if (popup.classList.contains("hidden")) {
            popup.classList.remove("hidden");
        } else {
            popup.classList.add("hidden");
        }
    }
</script>
