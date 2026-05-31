<x-head>

    <body
        class="min-h-screen bg-gray-50 text-gray-900 antialiased transition-colors duration-300 dark:bg-black dark:text-white">
        <div class="fixed right-4 top-4 z-50">
            <x-dark-mode-toggle :label="false" />
        </div>

        <div class="flex min-h-screen flex-col items-center px-4 pt-6 sm:justify-center sm:pt-0">
            <div class="mb-8">
                <a href={{ route('jobs.index') }}>
                    <x-app-logo width="120" />
                </a>
            </div>

            <div
                class="w-full rounded-lg border border-gray-200 bg-white px-8 py-8 shadow-lg transition-colors duration-300 sm:max-w-md dark:border-white/10 dark:bg-white/5">
                {{ $slot }}
            </div>
        </div>
    </body>

    </html>
</x-head>
