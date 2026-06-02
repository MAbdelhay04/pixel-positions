<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-gray-900 dark:text-white">
                    {{ __('Notifications') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Your recent alerts and updates.') }}
                </p>
            </div>

            @if (auth()->user()->unreadNotifications()->exists())
                <x-secondary-button type="button" data-mark-all-read data-mark-all-url="{{ route('notifications.read_all') }}">
                    {{ __('Mark all as read') }}
                </x-secondary-button>
            @endif
        </div>
    </x-slot>

    <main class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8" data-notifications-page>
        @include('notifications._list', ['notifications' => $notifications])
    </main>
</x-app-layout>
