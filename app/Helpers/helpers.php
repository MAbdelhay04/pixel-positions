<?php

if (! function_exists('searchLike')) {
    function searchLike(mixed $value)
    {
        return '%' . preg_replace('/\s+/', '%', trim((string)$value)) . '%';
    }
}

if (! function_exists('isAjax')) {
    function isAjax(): bool
    {
        return request()->ajax() || request()->wantsJson();
    }
}

if (! function_exists('notification_type_view')) {
    function notification_type_view(\Illuminate\Notifications\DatabaseNotification|string $notification): string
    {
        return \App\Support\Notifications\NotificationViewResolver::viewFor($notification);
    }
}

if (! function_exists('notification_redirect_url')) {
    function notification_redirect_url(\Illuminate\Notifications\DatabaseNotification $notification): string
    {
        return \App\Support\Notifications\NotificationViewResolver::redirectUrl($notification);
    }
}
