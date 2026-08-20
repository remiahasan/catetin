<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    <title>Pegawai Page</title>
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Tailwind & App CSS -->
    <link href="{{ asset('css/soft-ui-dashboard-tailwind.css') }}" rel="stylesheet" />
    @vite('resources/css/app.css')
    <!-- Nepcha Analytics -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <script src="{{ asset('js/modal/notifikasi.js') }}"></script>
</head>

<body class="min-h-screen m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">

    <!-- Sticky Navigation -->
    @include('components.layout.PegawaiLayout.body.header')

    <!-- Main Content -->
    <main class="pt-11 z-10 ease-soft-in-out relative h-full max-h-screen rounded-xl transition-all duration-200">
        <div class="w-full py-6 mx-auto">
            @yield('pegawai')
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/soft-ui-dashboard-tailwind.js') }}" async></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>

</html>
