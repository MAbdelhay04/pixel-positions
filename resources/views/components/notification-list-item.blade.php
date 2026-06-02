@props(['notification', 'compact' => false, 'url'])

@php
    $isUnread = is_null($notification->read_at);
    $url = $url ?? notification_redirect_url($notification);
@endphp

<button
    type="button"
    data-notification-id="{{ $notification->id }}"
    data-notification-url="{{ $url }}"
    data-notification-read-url="{{ route('notifications.read', $notification) }}"
    @class([
        'notification-item group w-full cursor-pointer text-left transition-colors duration-150',
        'border-b border-gray-100 px-4 py-3 last:border-b-0 dark:border-white/10' => $compact,
        'rounded-lg border p-4' => ! $compact,
        'border-indigo-200 bg-indigo-50/60 dark:border-indigo-500/30 dark:bg-indigo-500/10' => ! $compact && $isUnread,
        'border-gray-200 bg-white dark:border-white/10 dark:bg-white/5' => ! $compact && ! $isUnread,
        'hover:bg-gray-50 dark:hover:bg-white/5' => $compact,
        'bg-indigo-50/50 dark:bg-indigo-500/5' => $compact && $isUnread,
    ])
>
    <div class="flex items-start gap-3">
        @if ($isUnread)
            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-indigo-600 dark:bg-indigo-400" aria-hidden="true"></span>
        @else
            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-transparent" aria-hidden="true"></span>
        @endif

        <div class="min-w-0 flex-1">
            {{ $slot }}

            <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>
    </div>
</button>
