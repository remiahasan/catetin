<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start"
    navbar-main navbar-scroll="true">
    <div class="flex justify-between w-full py-1 mx-auto flex-wrap-inherit">
        <nav class="flex pt-1">
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 lg:hidden text-gray-600 focus:outline-none">
                <!-- Ikon hamburger -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="pl-4 ">
                <!-- breadcrumb -->
                <ol class="flex flex-wrapmr-12 bg-transparent rounded-lg sm:mr-16">

                    <li class="leading-normal text-sm">
                        <a class="opacity-50 text-slate-700" href="javascript:;">Pages</a>
                    </li>
                    <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']"
                        aria-current="page">@yield('title')</li>
                </ol>
                <h6 class="mb-0 font-bold capitalize">@yield('title')</h6>
            </div>
        </nav>

        <div class="flex items-center justify-end mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">



            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                <!-- online builder btn  -->
                <!-- <li class="flex items-center">
                <a class="inline-block px-8 py-2 mb-0 mr-4 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro border-fuchsia-500 ease-soft-in text-xs hover:scale-102 active:shadow-soft-xs text-fuchsia-500 hover:border-fuchsia-500 active:bg-fuchsia-500 active:hover:text-fuchsia-500 hover:text-fuchsia-500 tracking-tight-soft hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent" target="_blank" href="https://www.creative-tim.com/builder/soft-ui?ref=navbar-dashboard&amp;_ga=2.76518741.1192788655.1647724933-1242940210.1644448053">Online Builder</a>
              </li> -->
                <li class="mt-0.5">
                    <a class="{{ Request::routeIs('profile.view')
                        ? 'shadow-soft-xl text-sm ease-nav-brand my-0 pl-2 flex items-center whitespace-nowrap rounded-lg bg-white font-semibold transition-colors'
                        : 'text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap text-slate-700 transition-colors' }}"
                        href="{{ route('profile.view') }}">

                        <span class="ml-1 hidden md:block duration-300 opacity-100 pointer-events-none ease-soft">
                            {{ Auth::user()->name }}
                        </span>

                        <div
                            class="{{ Request::routeIs('profile.view')
                                ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl ml-2 flex h-8 w-8 items-center justify-center rounded-lg text-white md:p-2.5'
                                : 'shadow-soft-2xl ml-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-slate-700 md:p-2.5' }}">
                            <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>account-user</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1717.000000, -291.000000)" fill="currentColor"
                                        fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(1.000000, 0.000000)">
                                                <path
                                                    d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z">
                                                </path>
                                                <path
                                                    d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z">
                                                </path>
                                                <path
                                                    d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
