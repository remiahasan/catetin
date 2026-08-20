@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Manage Users')
@section('admin')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">

                <div class="px-6 flex my-9 items-center justify-between gap-2 mb-4">
                    <h6>Kelola Pegawai</h6>
                    <x-plus-button buttonAction="togglePopup('popup-add-user')" label="TAMBAH" :showLabel="true"
                        :button="true"/>
                </div>

                {{-- Modal Tambah User --}}
                <x-modal-add id="popup-add-user" title="Add User" :isEdit="false" action="{{ route('admin.add-user') }}"
                    method="POST" :inputs="[
                        [
                            'label' => 'Name',
                            'name' => 'name',
                            'type' => 'text',
                            'placeholder' => 'Name',
                            'required' => true,
                        ],
                        [
                            'label' => 'Email',
                            'name' => 'email',
                            'type' => 'email',
                            'placeholder' => 'Email',
                            'required' => true,
                        ],
                        [
                            'label' => 'Password',
                            'name' => 'password',
                            'type' => 'password',
                            'placeholder' => 'Password',
                            'required' => true,
                        ],
                        [
                            'label' => 'Business',
                            'name' => 'id_business',
                            'type' => 'select',
                            'options' => $businesses
                                ->map(fn($b) => ['value' => $b->id, 'label' => $b->name])
                                ->toArray(),
                            'required' => true,
                        ],
                    ]" />

                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Business</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        {{-- Kolom Name --}}
                                        <td
                                            class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <div class="flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $item->photo ? asset($item->photo) : asset('img/illustrations/face2.svg') }}"
                                                        class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-soft-in-out h-9 w-9 rounded-xl"
                                                        alt="user" />
                                                </div>
                                                <div class="flex flex-col justify-center">
                                                    <h6 class="mb-0 text-sm leading-normal">{{ $item->name }}</h6>
                                                    <p class="mb-0 text-xs leading-tight text-slate-400">
                                                        {{ $item->email }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom Business --}}
                                        <td
                                            class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs leading-tight text-slate-400">
                                                {{ $item->business->name }}
                                            </p>
                                        </td>

                                        {{-- Kolom Status (klik badge untuk toggle) --}}
                                        <td
                                            class="p-2 text-sm text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <form
                                                action="{{ route($item->is_verified ? 'admin.inverify-user' : 'admin.verify-user', $item) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 rounded-lg text-xs font-bold uppercase text-white 
                                                    {{ $item->is_verified
                                                        ? 'bg-gradient-to-tl from-green-600 to-lime-400'
                                                        : 'bg-gradient-to-tl from-slate-600 to-slate-300' }}">
                                                    {{ $item->is_verified ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>

                                        {{-- Kolom Action (hapus saja) --}}
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <a href="{{ route('admin.users.detail', $item->id) }}"
                                                class="text-blue-500 hover:underline text-sm leading-tight">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('components.ui.Modal.delete')
@endsection
