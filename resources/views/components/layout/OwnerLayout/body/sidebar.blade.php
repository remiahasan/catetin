@php
    $kelolaStokDropdown = [
        [
            'label' => 'Riwayat Stok',
            'route' => 'admin.stock-history',
        ],
        [
            'label' => 'Tambah Stok',
            'children' => $businesses->map(
                fn($b) => [
                    'label' => $b->name,
                    'route' => 'admin.manage-stock',
                    'params' => ['id' => $b->id],
                ],
            ),
        ],
    ];

    $businesItems = $businesses
        ->map(
            fn($b) => [
                'label' => $b->name,
                'route' => 'admin.laporan',
                'params' => ['id' => $b->id],
            ],
        )
        ->toArray();
@endphp
<aside
    class="max-w-62.5 ease-nav-brand z-50 fixed inset-y-0 my-4 block w-full  -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 lg:left-0 lg:translate-x-0 lg:bg-transparent"
    :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
    <div class="h-19.5">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 lg:hidden"
            sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700">
            {{-- <img src="{{ asset('img/logo-ct.png') }}"
                class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" /> --}}
            <svg class="w-6 h-6 inline max-w-full transition-all duration-200 ease-nav-brand max-h-8 text-gray-800"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 211.01 237.38" fill="currentColor">
                <g>
                    <path
                        d="m211.01,131.88c0,9.11-1.16,17.95-3.33,26.38-11.71,45.51-53.02,79.13-102.17,79.13S15.03,203.76,3.33,158.26c-2.17-8.43-3.33-17.27-3.33-26.38h26.38c0,9.25,1.59,18.13,4.5,26.38,9.45,26.72,32.84,46.86,61.44,51.66,4.29.72,8.69,1.09,13.19,1.09s8.9-.37,13.19-1.09c9.47-1.59,18.37-4.86,26.38-9.5,16.18-9.36,28.74-24.29,35.06-42.16h-87.82v-52.75H0c0-9.11,1.16-17.95,3.33-26.38,2.41-9.36,6.06-18.22,10.79-26.38C32.36,21.22,66.45,0,105.5,0s73.15,21.22,91.39,52.75c4.73,8.16,8.38,17.02,10.79,26.38,2.17,8.43,3.33,17.27,3.33,26.38h-26.38c0-9.25-1.59-18.13-4.5-26.38-3.48-9.83-8.84-18.77-15.66-26.38-5.58-6.24-12.12-11.57-19.41-15.78-8.01-4.64-16.91-7.91-26.38-9.5-4.29-.72-8.69-1.09-13.19-1.09s-8.9.37-13.19,1.09c-18.08,3.03-34.08,12.2-45.78,25.28-6.82,7.61-12.18,16.55-15.66,26.38h61.44v-26.38h26.38v26.38h26.38v26.38h-26.38v26.38h92.32Z" />
                </g>
            </svg>
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Owner Page</span>
        </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

    <div class="items-center block w-auto max-h-screen overflow-auto grow basis-full">

        <ul class="flex flex-col pl-0 mb-0">
            <x-sidebar-item route="owner.dashboard" label="Dashboard">
                <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>shop</title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(0.000000, 148.000000)">
                                    <path
                                        class="opacity-60 {{ Request::routeIs('owner.dashboard*') ? '' : 'fill-slate-800' }}"
                                        d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                    </path>
                                    <path class="{{ Request::routeIs('owner.dashboard*') ? '' : 'fill-slate-800' }}"
                                        d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg></x-sidebar-item>

            <x-sidebar-item route="admin.verify-users" label="Kelola Pengguna">
                <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>account-user</title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(1.000000, 0.000000)">
                                    <path
                                        class="opacity-60 {{ Request::routeIs('admin.verify-users') ? '' : 'fill-slate-800' }}"
                                        d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z">
                                    </path>
                                    <path class="{{ Request::routeIs('admin.verify-users*') ? '' : 'fill-slate-800' }}"
                                        d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z">
                                    </path>
                                    <path class="{{ Request::routeIs('admin.verify-users*') ? '' : 'fill-slate-800' }}"
                                        d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg></x-sidebar-item>

            <x-sidebar-item route="admin.kelola-bisnis" label="Kelola Bisnis">
                <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>office</title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(153.000000, 2.000000)">
                                    <path
                                        class="opacity-60 {{ Request::routeIs('admin.kelola-bisnis*') ? '' : 'fill-slate-800' }}"
                                        d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                    </path>
                                    <path class="{{ Request::routeIs('admin.kelola-bisnis*') ? '' : 'fill-slate-800' }}"
                                        d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </x-sidebar-item>
            <x-sidebar-item route="admin.manage-stock" label="Kelola Stok" :dropdownItems="$kelolaStokDropdown">
                <svg width="16" height="16" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>office</title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(153.000000, 2.000000)">
                                    <path class=" {{ Request::routeIs('admin.manage-stock*') ? '' : 'fill-slate-800' }}"
                                        d="M16 16.1362V29C15.832 28.9993 15.667 28.9563 15.52 28.875L4.52 22.8525C4.36293 22.7665 4.23181 22.64 4.14035 22.4861C4.04888 22.3322 4.00041 22.1565 4 21.9775V10.0225C4.0004 9.88241 4.03021 9.74401 4.0875 9.61621L16 16.1362Z">
                                    </path>
                                    <path
                                        class="opacity-60 {{ Request::routeIs('admin.manage-stock*') ? '' : 'fill-slate-800' }}"
                                        d="M27.96 8.26877L16.96 2.25002C16.6661 2.08763 16.3358 2.00244 16 2.00244C15.6642 2.00244 15.3339 2.08763 15.04 2.25002L4.04 8.27127C3.72586 8.44315 3.46363 8.69622 3.28069 9.00405C3.09775 9.31188 3.00081 9.66319 3 10.0213V21.9763C3.00081 22.3344 3.09775 22.6857 3.28069 22.9935C3.46363 23.3013 3.72586 23.5544 4.04 23.7263L15.04 29.7475C15.3339 29.9099 15.6642 29.9951 16 29.9951C16.3358 29.9951 16.6661 29.9099 16.96 29.7475L27.96 23.7263C28.2741 23.5544 28.5364 23.3013 28.7193 22.9935C28.9023 22.6857 28.9992 22.3344 29 21.9763V10.0225C28.9999 9.6638 28.9032 9.31172 28.7203 9.00317C28.5373 8.69462 28.2747 8.44096 27.96 8.26877ZM16 4.00002L26.0425 9.50002L22.3213 11.5375L12.2775 6.03752L16 4.00002ZM16 15L5.9575 9.50002L10.195 7.18002L20.2375 12.68L16 15ZM5 11.25L15 16.7225V27.4463L5 21.9775V11.25ZM27 21.9725L17 27.4463V16.7275L21 14.5388V19C21 19.2652 21.1054 19.5196 21.2929 19.7071C21.4804 19.8947 21.7348 20 22 20C22.2652 20 22.5196 19.8947 22.7071 19.7071C22.8946 19.5196 23 19.2652 23 19V13.4438L27 11.25V21.9713V21.9725Z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </x-sidebar-item>
            <x-sidebar-item route="admin.laporan" label="Laporan" :dropdownItems="$businesItems">
                <svg width="12" height="12" viewBox="0 0 19 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <title>office</title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(153.000000, 2.000000)">
                                    <path class="{{ Request::routeIs('admin.laporan*') ? '' : 'fill-slate-800' }}"
                                        d="M8.99902 0.953125C7.34212 0.953125 5.99912 2.29313 5.99902 3.95312V5.01562H4.99902C4.08452 4.97883 2.68092 5.57623 2.15522 7.04693L0.999023 7.01562C0.446723 7.01562 -0.00107655 7.46233 -0.000976546 8.01562C-0.000976546 8.56892 0.446723 9.01613 0.999023 9.01613H1.99902V10.0161H0.999023C0.446723 10.0161 -0.00107655 10.4621 -0.000976546 11.0161C-0.000976546 11.5691 0.446723 12.0151 0.999023 12.0161H1.99902V13.0161H0.999023C0.446723 13.0161 -0.00107655 13.4621 -0.000976546 14.0161C-0.000976546 14.5691 0.446723 15.0161 0.999023 15.0161H1.99902V16.0161H0.999023C0.446723 16.0161 -0.00107655 16.4621 -0.000976546 17.0161C-0.000976546 17.5691 0.446723 18.0161 0.999023 18.0161H1.99902C1.99902 19.6721 3.34212 21.0161 4.99902 21.0161H11.999C13.6559 21.0161 14.999 19.6721 14.999 18.0161V17.0161L15.999 16.9841C17.6559 16.9841 18.9989 15.6441 18.999 13.9841V3.95312C18.999 2.29323 17.6558 0.952925 15.999 0.953125H8.99902ZM15.999 2.95312C16.5513 2.95312 16.9991 3.39983 16.999 3.95312V13.9531C16.999 14.5061 16.5513 14.9531 15.999 14.9531L14.999 14.9841V8.01562C14.999 6.35873 13.6559 5.01562 11.999 5.01562H7.99902V3.98443C7.99902 3.43113 8.44672 2.98433 8.99902 2.98443L15.999 2.95312Z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </x-sidebar-item>

        </ul>
    </div>
</aside>
