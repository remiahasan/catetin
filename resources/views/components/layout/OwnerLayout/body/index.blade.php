<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    <title>Owner Page</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    {{-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> --}}
    <!-- Nucleo Icons -->
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/datepicker-light.css') }}">
    <!-- Popper -->
    {{-- <script src="https://unpkg.com/@popperjs/core@2"></script> --}}
    <!-- Main Styling -->
    <link href="{{ asset('css/soft-ui-dashboard-tailwind.css') }}" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body x-data="{ sidebarOpen: false }"
    class="m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">

    @include('components.layout.OwnerLayout.body.sidebar')

    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        @click="sidebarOpen = false">
    </div>

    <main class="ease-soft-in-out lg:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
        @include('components.layout.OwnerLayout.body.header')

        <!-- body -->
        <div class="w-full px-4 py-6 mx-auto">
            @yield('admin')

            {{-- @include('components.layout.OwnerLayout.body.footer') --}}
        </div>

    </main>

</body>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


<!-- main script file  -->
{{-- <script src="{{ asset('js/soft-ui-dashboard-tailwind.js') }}" async></script> --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/modal/delet.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

</html>
