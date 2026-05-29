@props(['disabled' => false])

<select @disabled($disabled) {{ $attributes->merge([
    'class' => 'block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 transition-all duration-150 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 disabled:cursor-not-allowed disabled:opacity-50 dark:border-white/10 dark:bg-black dark:text-white dark:focus:border-blue-700 dark:focus:ring-blue-700 [&>option]:bg-white [&>option]:text-gray-900 dark:[&>option]:bg-black dark:[&>option]:text-white',
]) }}>
    {{ $slot }}
</select>
