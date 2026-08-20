<div>
    @props([
        'type' => 'button',
        'color' => 'gray',
    ])

    @php
        $baseClass = 'px-6 py-1 rounded-lg text-sm font-medium focus:outline-none transition';
        $colorClass = match ($color) {
            'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
            'secondary' => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
            'danger' => 'bg-red-600 text-white hover:bg-red-700',
            default => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
        };
    @endphp

    <button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClass $colorClass"]) }}>
        {{ $slot }}
    </button>
</div>
