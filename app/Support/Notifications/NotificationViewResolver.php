<?php

namespace App\Support\Notifications;

use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Notifications\DatabaseNotification;

class NotificationViewResolver
{
    /** @var array<class-string, string> */
    private const TYPE_VIEWS = [
        ApplicationStatusUpdated::class => 'notifications.types.application-status-updated',
    ];

    public static function viewFor(DatabaseNotification|string $notification): string
    {
        $type = $notification instanceof DatabaseNotification
            ? $notification->type
            : $notification;

        return self::TYPE_VIEWS[$type] ?? 'notifications.types.default';
    }

    public static function redirectUrl(DatabaseNotification $notification): string
    {
        return $notification->data['url'] ?? route('dashboard');
    }
}
