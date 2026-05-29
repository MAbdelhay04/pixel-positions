<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex cursor-pointer items-center justify-center px-5 py-2.5 rounded-lg border font-semibold text-xs uppercase tracking-widest transition-all duration-150 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-40 disabled:cursor-not-allowed
        bg-white border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-900 focus:ring-gray-300 focus:ring-offset-white
        dark:bg-transparent dark:border-white/10 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-black'
]) }}>
    {{ $slot }}
</button>
