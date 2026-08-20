@props(['label', 'buttonAction', 'showLabel' => false])


@if ($buttonAction)
    <button onclick="{!! $buttonAction !!}"
        class="flex bg-gradient-fuchsia text-white items-center gap-2 px-4 py-3 rounded-lg text-xs font-bold
        hover:bg-gray-50 transition">
        <svg class="w-2.5 h-2.5" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
            <path d="M8.5 1V16M1 8.5H16" />
        </svg>
        <span class="{{ $showLabel ? 'hidden md:inline' : '' }}">{{ $label }}</span>


    </button>
@endif
 