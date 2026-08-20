@extends('components.layout.PegawaiLayout.body.index')

@section('pegawai')
    <div class="p-6">
        <x-ui.bg-pink />
        <div class="relative -mt-16 justify-center w-full">
            <div
                class="mb-4 relative max-w-lg md:mx-auto mx-3 overflow-hidden h-full bg-gray-100 rounded-2xl shadow-md flex items-center p-4">
                <img src="{{ asset('img/illustrations/pegawai.svg') }}" class="w-30 absolute bottom-0" alt="">
                <x-right-motif />
                <x-left-motif />

                <div class="pl-[8rem] md:pl-[9rem] text-white mr-auto pb-5 z-10">
                    <h2 class="text-2xl text-white font-bold md:text-slate-700">{{ $user->name }}</h2>
                    <p class="text-sm md:text-slate-700">Usaha {{ $business->name }}</p>
                    <p class="text-sm md:text-slate-700">{{ $transaksi->count() }} Transaksi</p>
                </div>
            </div>

            <x-form-stok :stocks="$stocks" :business="$business" mode="update" :alreadyUpdated="$alreadyUpdated" />

        </div>
    </div>

    <script>
        function togglePopup(id) {
            const popup = document.getElementById(id);
            popup.classList.toggle("hidden");
        }

        document.getElementById("btn-save-stok").addEventListener("click", () => {

            let isValid = true;
            let warningText = "Ada data stok yang melebihi stok awal:<br>";

            document.querySelectorAll("input[name^='jumlah_stok']").forEach(input => {
                const max = parseInt(input.dataset.max);
                const val = parseInt(input.value);
                const nama = input.dataset.nama; // ambil nama stok

                input.classList.remove("border-red-500");

                if (val > max) {
                    isValid = false;
                    input.classList.add("border-red-500");
                    warningText += `- ${nama} (Maks: ${max}, Input: ${val})<br>`;
                }
            });

            if (!isValid) {
                document.getElementById("warning-text").innerHTML = warningText;
                togglePopup("warning-modal");
            } else {
                togglePopup("update-modal");
            }
        });

        // override supaya modal submit form utama
        document.addEventListener("DOMContentLoaded", () => {
            const modalForm = document.querySelector("#update-stok-form");
            const mainForm = document.querySelector("#form-update-stok");

            modalForm.addEventListener("submit", function(e) {
                e.preventDefault();
                mainForm.submit(); // submit form utama
            });
        });
    </script>
@endsection
