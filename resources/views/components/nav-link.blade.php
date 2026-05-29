@props(['active'])

@php
$classes = ($active ?? false)
? 'inline-flex items-center px-1 py-0.5 border-b-2 text-sm font-semibold transition duration-150 ease-in-out
focus:outline-none border-blue-600 text-gray-900 dark:text-white'
: 'inline-flex items-center px-1 py-0.5 border-b-2 border-transparent text-sm font-medium transition duration-150
ease-in-out focus:outline-none text-gray-500 hover:text-gray-800 hover:border-gray-400 dark:text-gray-500
dark:hover:text-white dark:hover:border-white/30';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
