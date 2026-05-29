@props([
    'disabled' => false,
    'accept' => null,
])

<input type="file"
    @disabled($disabled)
    @if($accept) accept="{{ $accept }}" @endif
    {{ $attributes->merge([
        'class' => 'block w-full text-sm cursor-pointer focus:outline-none rounded-lg border transition-colors duration-150
            text-gray-500 border-gray-300 bg-white
            file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border file:border-gray-300 file:bg-gray-50 file:text-gray-600 file:text-xs file:font-semibold file:uppercase file:tracking-wider
            hover:file:bg-gray-100 hover:file:text-gray-900 file:transition-all file:duration-150
            dark:text-gray-500 dark:border-white/10 dark:bg-white/5
            dark:file:border-white/10 dark:file:bg-white/5 dark:file:text-gray-300
            dark:hover:file:bg-white/10 dark:hover:file:text-white'
    ]) }}
>
