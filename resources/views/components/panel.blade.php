@php
$classes = 'p-6 rounded-lg border transition-colors duration-200
bg-white border-gray-200 shadow-sm hover:border-blue-300
dark:bg-white/5 dark:border-white/10 dark:hover:border-blue-700/60';
@endphp

<div {{ $attributes(['class'=> $classes]) }}>
    {{ $slot }}
</div>
