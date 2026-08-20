@props(['title', 'button' => false, 'buttonAction' => null, 'label', 'showLabel' => false])

<div class="px-6 flex text-xl font-semibold py-6 items-center justify-between gap-2">
    <h6>{{ $title }}</h6>


    {{-- @if ($button === true)
        <x-plus-button buttonAction="{{ $buttonAction }}" label="{{ $label }}" :showLabel="$showLabel" />
    @endif --}}


    @if ($button == true)
        <button onclick="{!! $buttonAction !!}"
            class="flex bg-gradient-fuchsia text-white items-center gap-2 px-4 py-3 rounded-lg text-xs font-bold
        hover:bg-gray-50 transition">
            <svg class="w-3 h-3" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.5 1V16M1 8.5H16" />
            </svg>
            <span class="{{ $showLabel ? 'hidden md:inline' : '' }}">{{ $label }}</span>
        </button>
    @endif

</div>
