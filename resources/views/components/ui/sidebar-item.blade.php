@props([
    'route' => null,
    'label' => '',
    'icon' => null,
    'dropdownItems' => null,
])

@php
    $isActive = Request::routeIs(is_array($route) ? $route[0] . '*' : $route . '*');
@endphp

<li
    @if ($dropdownItems) x-data="{ open: false }" class="relative"
    @else
        class="mt-0.5 w-full" @endif>

    {{-- Jika punya dropdown --}}
    @if ($dropdownItems)
        <button @click="open = !open"
            class="flex items-center w-full px-8 py-3 text-sm transition-all duration-200 ease-nav-brand rounded-lg
                {{ $isActive
                    ? 'shadow-soft-xl py-2.7 text-sm my-0 whitespace-nowrap rounded-lg bg-white px-4 font-semibold text-slate-700 transition-colors'
                    : 'py-2.7 text-sm ease-nav-brand whitespace-nowrap transition-colors' }}">

            @if ($icon)
                <i class="fa {{ $icon }} mr-3"></i>
            @else
                <div
                    class="{{ $isActive
                        ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg'
                        : 'bg-white text-slate-800 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg' }}">
                    {{ $slot }}
                </div>
            @endif

            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ $label }}</span>
            <svg class="ml-auto w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Dropdown level 1 --}}
        <ul x-show="open" x-transition class="pl-12 space-y-1 bg-gray-50 rounded-md py-2">
            @foreach ($dropdownItems as $item)
                {{-- Jika punya submenu (children) --}}
                @if (isset($item['children']))
                    <li x-data="{ openChild: false }">
                        <button @click="openChild = !openChild"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100">
                            {{ $item['label'] }}
                            <svg class="ml-auto w-3 h-3 transition-transform" :class="{ 'rotate-180': openChild }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown level 2 --}}
                        <ul x-show="openChild" x-transition class="pl-6 mt-1 space-y-1">
                            @foreach ($item['children'] as $child)
                                <li>
                                    <a href="{{ route($child['route'], $child['params'] ?? []) }}"
                                        class="block px-4 py-2 text-sm rounded-md text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    {{-- Item biasa dalam dropdown --}}
                    <li>
                        @if (!empty($item['route']))
                            <a href="{{ route($item['route'], $item['params'] ?? []) }}"
                                class="block px-4 py-2 text-sm rounded-md text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                {{ $item['label'] }}
                            </a>
                        @else
                            <span class="block px-4 py-2 text-sm rounded-md text-gray-400 cursor-not-allowed">
                                {{ $item['label'] }}
                            </span>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>

        {{-- Jika bukan dropdown --}}
    @else
        <a href="{{ is_array($route) ? route($route[0], $route[1]) : route($route) }}"
            class="{{ $isActive
                ? 'py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-white px-4 font-semibold text-slate-700 transition-colors'
                : 'py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors' }}">
            @if ($icon)
                <i class="fa {{ $icon }} mr-3"></i>
            @else
                <div
                    class="{{ $isActive
                        ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg text-white'
                        : 'shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-slate-800' }}">
                    {{ $slot }}
                </div>
            @endif
            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ $label }}</span>
        </a>
    @endif
</li>
