@props(['notification', 'compact' => false])

@php
    $data = $notification->data;
    $message = $data['message'] ?? __('You have a new notification.');
    $url = notification_redirect_url($notification);
    $isUnread = is_null($notification->read_at);
@endphp

<x-notification-list-item :notification="$notification" :compact="$compact" :url="$url">
    <p @class([
        'text-sm leading-snug',
        'font-semibold text-gray-900 dark:text-white' => $isUnread,
        'text-gray-700 dark:text-gray-300' => ! $isUnread,
    ])>
        {{ $message }}
    </p>
</x-notification-list-item>
