<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex cursor-pointer items-center justify-center px-5 py-2.5 rounded-lg border font-semibold text-xs uppercase tracking-widest transition-all duration-150 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-40 disabled:cursor-not-allowed
        bg-gray-900 border-gray-700 text-white hover:bg-gray-800 focus:ring-gray-700 focus:ring-offset-white
        dark:bg-white/10 dark:border-white/10 dark:text-white dark:hover:bg-white/20 dark:focus:ring-white/20 dark:focus:ring-offset-black'
]) }}>
    {{ $slot }}
</button>
