@props(['active'])

@php
$classes = ($active ?? false)
? 'block w-full px-3 py-2.5 rounded-lg text-sm font-semibold border transition duration-150 ease-in-out
focus:outline-none bg-gray-100 border-gray-200 text-gray-900 dark:bg-white/10 dark:border-white/10 dark:text-white'
: 'block w-full px-3 py-2.5 rounded-lg text-sm font-medium border border-transparent transition duration-150 ease-in-out
focus:outline-none text-gray-600 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-200 dark:text-gray-400
dark:hover:text-white dark:hover:bg-white/5 dark:hover:border-white/10';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
