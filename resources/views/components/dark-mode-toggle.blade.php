@props([
'label' => true,
'class' => '',
])

<button type="button" onclick="toggleDarkMode()" aria-label="Toggle dark mode"
    class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg p-2 text-gray-600 transition-colors duration-200 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900 {{ $class }}">
    <svg class="hidden h-5 w-5 shrink-0 dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364-.707-.707M6.343 6.343l-.707-.707m12.728 0-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
    </svg>

    <svg class="block h-5 w-5 shrink-0 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20.354 15.354A9 9 0 0 1 8.646 3.646 9.003 9.003 0 0 0 12 21a9.003 9.003 0 0 0 8.354-5.646z" />
    </svg>

    @if ($label)
    <span class="sr-only text-xs font-semibold uppercase tracking-wider sm:not-sr-only">
        <span class="dark:hidden">Dark mode</span>
        <span class="hidden dark:inline">Light mode</span>
    </span>
    @endif
</button>
