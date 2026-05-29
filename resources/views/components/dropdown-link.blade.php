<a {{ $attributes->merge(['class' => 'block px-4 py-2.5 text-sm font-medium text-gray-600 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-950 focus:bg-gray-100 focus:text-gray-950 focus:outline-none dark:text-gray-300 dark:hover:bg-white/10 dark:hover:text-white dark:focus:bg-white/10 dark:focus:text-white']) }}>
    {{ $slot }}
</a>
