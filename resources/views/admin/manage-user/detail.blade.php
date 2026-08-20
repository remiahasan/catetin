@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Detail Pegawai')

@section('admin')
    <div class="p-6">
        <x-ui.bg-pink class="h-75" />

        {{-- Kartu Profil --}}
        <div
            class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words 
        border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">

            <div class="flex flex-wrap justify-between -mx-3">
                <div class="flex items-center px-3 space-x-4">
                    <!-- FOTO PROFIL -->
                    <div class="relative">
                        <div id="profilePhotoBtn"
                            class="text-base ease-soft-in-out h-18.5 overflow-hidden w-18.5 relative inline-flex 
                               items-center justify-center rounded-xl text-white transition-all duration-200 cursor-pointer">
                            <img src="{{ $user->photo ? asset($user->photo) : asset('img/illustrations/face2.svg') }}"
                                alt="profile_image" class="w-full h-full shadow-soft-sm object-cover rounded-xl" />
                        </div>
                    </div>

                    <div>
                        <h5 class="mb-1 text-lg">{{ $user->name }}</h5>
                        <p class="mb-0 font-semibold leading-normal text-sm capitalize">{{ $user->role }}</p>
                    </div>
                </div>

                <!-- Tombol Hapus -->
                <div class="px-3 flex items-center">
                    <button onclick="togglePopup('delete-pegawai-{{ $user->id }}')"
                        class="absolute top-1/2 right-4 transform -translate-y-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            aria-hidden="true" role="img">
                            <title>Hapus</title>
                            <path fill="#EF4444"
                                d="M9 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1h4a1 1 0 1 1 0 2h-1.092l-1.27 14.338A3 3 0 0 1 13.65 22H10.35a3 3 0 0 1-2.988-2.662L6.092 5H5a1 1 0 1 1 0-2h4Zm2.5 6a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0v-6a1 1 0 0 0-1-1ZM9 6h6v1H9V6Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <x-modal-delete id="delete-pegawai-{{ $user->id }}" :action="route('admin.users.delete', $user->id)" />

        {{-- Informasi Profil --}}
        <div class="mt-6 p-6 rounded-3 shadow-xl bg-white">
            <div class="flex justify-between">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase">Profile Information</h3>
                <p onclick="openEdit()" class="text-blue-500 text-sm cursor-pointer font-semibold">Edit</p>
            </div>

            {{-- Tampilan View --}}
            <div id="profile-view">
                <div class="flex items-center justify-between py-2 border-b">
                    <div>
                        <p class="text-xs text-gray-500">Full Name</p>
                        <p class="text-sm text-gray-700">{{ $user->name }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2 border-b">
                    <div>
                        <p class="text-xs text-gray-500">Email address</p>
                        <p class="text-sm text-gray-700">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2 border-b">
                    <div>
                        <p class="text-xs text-gray-500">Password</p>
                        <p class="text-sm text-gray-700">********</p>
                    </div>
                </div>
            </div>

            {{-- Tampilan Edit --}}
            <div id="profile-edit" class="hidden">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-xs text-gray-500">Full Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs text-gray-500">Email address</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded p-2">
                    </div>
                    <div class="flex justify-center gap-2">
                        <button type="button" onclick="cancelEdit()" class="px-4 py-2 border rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEdit() {
            document.getElementById('profile-view').classList.add('hidden');
            document.getElementById('profile-edit').classList.remove('hidden');
        }

        function cancelEdit() {
            document.getElementById('profile-edit').classList.add('hidden');
            document.getElementById('profile-view').classList.remove('hidden');
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showPopup("{{ session('success') }}", "success");
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showPopup("{{ session('error') }}", "error");
            });
        </script>
    @endif
@endsection
