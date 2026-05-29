<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex cursor-pointer items-center justify-center px-5 py-2.5 rounded-lg border font-semibold text-xs uppercase tracking-widest transition-all duration-150 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-40 disabled:cursor-not-allowed
        bg-red-600 border-red-700 text-white hover:bg-red-700 focus:ring-red-500 focus:ring-offset-white
        dark:bg-red-900/20 dark:border-red-800/50 dark:text-red-400 dark:hover:bg-red-900/40 dark:hover:text-red-300 dark:focus:ring-red-800/50 dark:focus:ring-offset-black'
]) }}>
    {{ $slot }}
</button>
