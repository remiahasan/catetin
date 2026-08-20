@extends(Auth::user()->role === 'pegawai' ? 'components.layout.PegawaiLayout.body.index' : 'components.layout.OwnerLayout.body.index')

@section(Auth::user()->role === 'pegawai' ? 'pegawai' : 'admin')
@section('title', 'Profile')
<div class="p-6">
    <x-ui.bg-pink class="h-75" />
    <div
        class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-auto max-w-full px-3">
                <!-- FOTO PROFIL -->
                <div class="relative">
                    <!-- Tombol Foto Profil -->
                    <div id="profilePhotoBtn"
                        class="text-base ease-soft-in-out h-18.5 overflow-hidden w-18.5 relative inline-flex items-center justify-center rounded-xl text-white transition-all duration-200 cursor-pointer">

                        <img src="{{ $user->photo
                            ? asset($user->photo)
                            : ($user->role == 'pegawai'
                                ? asset('img/illustrations/face2.svg')
                                : asset('img/illustrations/face1.svg')) }}"
                            alt="profile_image" class="w-full h-full shadow-soft-sm object-cover rounded-xl" />
                    </div>
                </div>
            </div>
            <!-- DROPDOWN -->
            <div id="dropdownProfile"
                class="absolute z-50 mt-2 w-40 bg-white rounded-lg shadow-lg text-gray-700 hidden">
                <button id="editPhotoBtn" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                    Edit Foto
                </button>
                <button class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                    Hapus Foto
                </button>
            </div>
            <div class="flex-none w-auto max-w-full px-3 my-auto">
                <div class="h-full">
                    <h5 class="mb-1 text-lg">{{ $user->name }}</h5>
                    <p class="mb-0 font-semibold leading-normal text-sm">{{ $user->role }}</p>
                </div>
            </div>
            <div class="w-full max-w-full px-3 mx-auto mt-4 sm:my-auto sm:mr-0 lg:w-40">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full border flex items-center justify-center bg-white font-semibold  text-red-500 py-1 drop-shadow-md rounded-md transition">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="mr-2"
                            xmlns="http://www.w3.org/2000/svg">
                            <path class="fill-red-500"
                                d="M2 12L1.21913 11.3753L0.719375 12L1.21913 12.6247L2 12ZM11 13C11.5523 13 12 12.5523 12 12C12 11.4477 11.5523 11 11 11V13ZM5.21913 6.3753L1.21913 11.3753L2.78087 12.6247L6.78087 7.6247L5.21913 6.3753ZM1.21913 12.6247L5.21913 17.6247L6.78087 16.3753L2.78087 11.3753L1.21913 12.6247ZM2 13H11V11H2V13Z" />
                            <path class="stroke-red-500"
                                d="M10 8.13193V7.38851C10 5.77017 10 4.961 10.474 4.4015C10.9479 3.84201 11.7461 3.70899 13.3424 3.44293L15.0136 3.1644C18.2567 2.62388 19.8782 2.35363 20.9391 3.25232C22 4.15102 22 5.79493 22 9.08276V14.9172C22 18.2051 22 19.849 20.9391 20.7477C19.8782 21.6464 18.2567 21.3761 15.0136 20.8356L13.3424 20.5571C11.7461 20.291 10.9479 20.158 10.474 19.5985C10 19.039 10 18.2298 10 16.6115V16.066"
                                stroke-width="2" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Settings & Profile --}}
    <div class="mt-6 p-6 rounded-3 shadow-xl bg-white">
        <div class="flex justify-between">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase">Profile Information</h3>
            <p onclick="openEdit()" class="text-blue-500 text-sm cursor-pointer font-semibold">Edit</p>
        </div>

        {{-- Full Name --}}
        <div id="profile-view">
            {{-- Full Name --}}
            <div class="flex items-center justify-between py-2 border-b">
                <div>
                    <p class="text-xs text-gray-500">Full Name</p>
                    <p class="text-sm text-gray-700">{{ $user->name }}</p>
                </div>
            </div>
            {{-- Email --}}
            <div class="flex items-center justify-between py-2 border-b">
                <div>
                    <p class="text-xs text-gray-500">Email address</p>
                    <p class="text-sm text-gray-700">{{ $user->email }}</p>
                </div>
            </div>
            {{-- Password --}}
            <div class="flex items-center justify-between py-2 border-b">
                <div>
                    <p class="text-xs text-gray-500">Password</p>
                    <p class="text-sm text-gray-700">********</p>
                </div>
            </div>
        </div>

        <div id="profile-edit" class="hidden">
            <form action="{{ route('profile.update') }}" method="POST">
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
                <div class="mb-4">
                    <label class="block text-xs text-gray-500">Password Lama</label>
                    <input type="password" name="old_password" class="w-full border rounded p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-xs text-gray-500">Password Baru</label>
                    <input type="password" name="new_password" class="w-full border rounded p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-xs text-gray-500">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="w-full border rounded p-2">
                </div>
                <div class="flex justify-center gap-2">
                    <button type="button" onclick="cancelEdit()" class="px-4 py-2 border rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL UPLOAD FOTO -->
    <div id="modalUpload" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Upload Foto Profil</h2>
            <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to
                                upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500">SVG, PNG, JPG or HEIC (MAX. 5mb)</p>
                    </div>
                    <input id="dropzone-file" name="photo" type="file" class="hidden" />
                </label>
                <div class="flex justify-center mt-4 space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Save
                    </button>
                    <button type="button" id="closeModalBtn"
                        class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/editProfile.js') }}"></script>

@endsection
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
