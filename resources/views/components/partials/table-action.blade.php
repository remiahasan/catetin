@props(['stocks', 'buttonAction' => '', 'dataId' => ''])

<button type="button"  class="text-blue-500 hover:text-blue-700 mx-auto block edit-stock-button"
    data-id="{{ $stocks }}" onclick="{{ $buttonAction }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
        <path fill-rule="evenodd" d="M4 16a1 1 0 001 1h10a1 1 0 100-2H5a1 1 0 00-1 1z" clip-rule="evenodd" />
    </svg>
</button>


