@auth
    @php
        $unreadCount = auth()->user()->unreadNotifications()->count();
        $notifications = auth()->user()->notifications()->latest()->limit(10)->get();
    @endphp

    <div class="relative" data-notification-bell data-dropdown-url="{{ route('notifications.dropdown') }}"
        data-mark-all-url="{{ route('notifications.read_all') }}">
        <button type="button" data-notification-trigger
            class="relative inline-flex cursor-pointer items-center justify-center rounded-lg border border-gray-200 bg-white p-2 text-gray-600 shadow-sm transition-colors duration-200 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white/20 dark:hover:bg-white/10 dark:hover:text-white"
            aria-label="{{ __('Notifications') }}" aria-expanded="false">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>

            <span data-notification-badge
                @class([
                    'absolute -end-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-bold text-white dark:bg-indigo-500',
                    'hidden' => $unreadCount === 0,
                ])>
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        </button>

        <div data-notification-panel
            class="absolute end-0 z-50 mt-2 hidden w-80 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg ring-1 ring-black/5 dark:border-white/10 dark:bg-black dark:ring-white/10">
            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-white/10">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Notifications') }}</p>
                <button type="button" data-mark-all-read
                    @class([
                        'text-xs font-medium text-indigo-600 transition-colors hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300',
                        'invisible' => $unreadCount === 0,
                    ])>
                    {{ __('Mark all read') }}
                </button>
            </div>

            <div data-notification-dropdown-content class="max-h-96 overflow-y-auto">
                @include('notifications._dropdown', ['notifications' => $notifications])
            </div>

            <div class="border-t border-gray-100 px-4 py-2.5 dark:border-white/10">
                <a href="{{ route('notifications.index') }}"
                    class="block text-center text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('View all notifications') }}
                </a>
            </div>
        </div>
    </div>
@endauth
