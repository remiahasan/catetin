<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    <title>Register</title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Main Styling -->
    <link href="{{ asset('css/soft-ui-dashboard-tailwind.css') }}" rel="stylesheet" />

    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="m-0 font-sans antialiased font-normal bg-white text-start text-base leading-default text-slate-500">

    <!-- Navbar -->
    <nav
        class="absolute top-0 z-30 flex flex-wrap items-center justify-between w-full px-4 py-2 mt-6 mb-4 shadow-none lg:flex-nowrap lg:justify-start">
        <div class="container flex items-center justify-between py-0 flex-wrap-inherit">
            <a class="py-2.375 text-sm mr-4 ml-4 whitespace-nowrap font-bold text-white lg:ml-0" href="#">
                Register Page </a>
        </div>
    </nav>

    <main class="mt-0 transition-all duration-200 ease-soft-in-out">
        <section class="min-h-screen mb-32">
            <div class="relative flex items-start pt-12 pb-56 m-4 overflow-hidden bg-center bg-cover min-h-50-screen rounded-xl"
                style="background-image: url({{ asset('img/curved-images/curved14.jpg') }})">
                <span
                    class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-gray-900 to-slate-800 opacity-60"></span>
                <div class="container z-10">
                    <div class="flex flex-wrap justify-center -mx-3">
                        <div class="w-full max-w-full px-3 mx-auto mt-0 text-center lg:flex-0 shrink-0 lg:w-5/12">
                            <h1 class="mt-12 mb-2 text-white">Welcome!</h1>
                            <p class="text-white">Use these awesome forms to login or create new account in your project
                                for free.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="flex flex-wrap -mx-3 -mt-48 md:-mt-56 lg:-mt-48">
                    <div class="w-full max-w-full px-3 mx-auto mt-0 md:flex-0 shrink-0 md:w-7/12 lg:w-5/12 xl:w-4/12">
                        <div
                            class="relative z-0 flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                            <div class="mt-4 p-6 mb-0 text-center bg-white border-b-0 rounded-t-2xl">
                                <h5>Register</h5>
                            </div>

                            <div class="flex-auto p-6">
                                <form role="form text-left" method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <!-- Name -->
                                    <x-input-label for="name" :value="__('Name')" />
                                    <div class="mb-4">
                                        <x-text-input id="name" type="text" name="name" :value="old('name')"
                                            required autofocus autocomplete="name" placeholder="Name" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <!-- jenis usaha -->
                                    <div class="mb-4">
                                        <x-input-label
                                            class="mb-1 ml-1 font-normal cursor-pointer select-none text-sm text-slate-700"
                                            for="jenis_usaha" :value="__('Jenis Usaha')" />

                                        <select id="id_business" name="id_business"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full
                                            appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding
                                            py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300
                                            focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                            required>
                                            <option value="" disabled selected>Pilih Jenis Usaha</option>
                                            @foreach ($businesses as $business)
                                                <option value="{{ $business->id }}">{{ $business->business_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('jenis_usaha')" class="mt-2" />
                                    </div>

                                    <!-- Email Address -->
                                    <div class="mb-4">
                                        <x-input-label
                                            class="mb-1 ml-1 font-normal cursor-pointer select-none text-sm text-slate-700"
                                            for="email" :value="__('Email')" />
                                        <x-text-input id="email"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                            type="email" name="email" :value="old('email')" required
                                            autocomplete="username" placeholder="Email" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-4">
                                        <x-input-label
                                            class="mb-1 ml-1 font-normal cursor-pointer select-none text-sm text-slate-700"
                                            for="password" :value="__('Password')" />

                                        <x-text-input id="password"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                            placeholder="Password" type="password" name="password" required
                                            autocomplete="new-password" />

                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-4">
                                        <x-input-label
                                            class="mb-1 ml-1 font-normal cursor-pointer select-none text-sm text-slate-700"
                                            for="password_confirmation" :value="__('Confirm Password')" />

                                        <x-text-input id="password_confirmation"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                            placeholder="Confirm Password" type="password" name="password_confirmation"
                                            required autocomplete="new-password" />

                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>

                                    <!-- Button -->
                                    <div class="text-center">
                                        <x-primary-button
                                            class="flex justify-center items-center w-full px-6 py-3 mt-6 mb-2 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer active:opacity-85 hover:scale-102 hover:shadow-soft-xs leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 bg-gradient-to-tl from-gray-900 to-slate-800 hover:border-slate-700 hover:bg-slate-700 hover:text-white ms-4">
                                            {{ __('Register') }}
                                        </x-primary-button>
                                    </div>
                                    <p class="mt-4 mb-0 leading-normal text-sm">Already have an account? <a
                                            href="{{ route('login') }}" class="font-bold text-slate-700">Sign in</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
{{-- <!-- plugin for scrollbar  -->
<script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}" async></script>
<!-- main script file  -->
<script src="{{ asset('js/soft-ui-dashboard-tailwind.js') }}" async></script> --}}

</html>
