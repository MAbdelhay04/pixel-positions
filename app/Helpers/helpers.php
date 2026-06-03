<?php

use App\Support\Notifications\NotificationViewResolver;
use Illuminate\Notifications\DatabaseNotification;

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
    function notification_type_view(DatabaseNotification|string $notification): string
    {
        return NotificationViewResolver::viewFor($notification);
    }
}

if (! function_exists('notification_redirect_url')) {
    function notification_redirect_url(DatabaseNotification $notification): string
    {
        return NotificationViewResolver::redirectUrl($notification);
    }
}

if (! function_exists('skillsMatchScore')) {
    function skillsMatchScore(array $userSkills, array $jobSkills): float
    {
        if (count($jobSkills) === 0) {
            return 0.0;
        }

        $matchedSkills = array_intersect($userSkills, $jobSkills);
        $score = (count($matchedSkills) / count($jobSkills)) * 100;

        return round($score, 2);
    }
}
