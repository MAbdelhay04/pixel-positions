@props(['disabled' => false])

@php
$isPassword = $attributes->get('type') === 'password';
$classes = 'block w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all duration-150 focus:outline-none
focus:ring-1 disabled:opacity-50 disabled:cursor-not-allowed
bg-white border-gray-300 text-gray-900 placeholder-gray-400 focus:border-blue-600 focus:ring-blue-600
dark:bg-white/5 dark:border-white/10 dark:text-white dark:placeholder-gray-600 dark:focus:border-blue-700
dark:focus:ring-blue-700';
@endphp

@if ($isPassword)
<div data-password-field class="relative">
    <input @disabled($disabled) {{ $attributes->merge(['class' => $classes . ' pr-11']) }}>

    <button type="button" data-password-toggle aria-label="Show password" tabindex="-1"
        class="absolute inset-y-0 right-0 inline-flex cursor-pointer items-center justify-center rounded-r-lg px-3 text-gray-500 transition-colors duration-150 hover:text-gray-900 focus:outline-none dark:text-gray-400 dark:hover:text-white">
        <svg data-password-icon="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
        </svg>

        <svg data-password-icon="hide" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10.585 10.585A2 2 0 0 0 12 14a2 2 0 0 0 1.415-.585" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.88 4.24A10.35 10.35 0 0 1 12 4c4.478 0 8.268 3.11 9.542 8a11.78 11.78 0 0 1-2.043 3.592" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6.228 6.228C4.46 7.52 3.15 9.46 2.458 12 3.732 16.89 7.523 20 12 20c1.49 0 2.89-.344 4.122-.956" />
        </svg>
    </button>
</div>
@else
<input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
@endif
