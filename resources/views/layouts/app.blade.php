<x-head>

    <body class="bg-gray-50 text-gray-900 antialiased transition-colors duration-300 dark:bg-black dark:text-white">
        @include('layouts.navigation')

        @isset($header)
        <header
            class="border-b border-gray-200 bg-white transition-colors duration-300 dark:border-white/10 dark:bg-black">
            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                <div class="text-gray-900 dark:text-white">
                    {{ $header }}
                </div>
            </div>
        </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </body>

    </html>
</x-head>
