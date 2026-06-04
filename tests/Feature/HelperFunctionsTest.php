<?php

use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

describe('searchLike', function () {
    it('wraps trimmed values in SQL wildcard characters', function () {
        expect(searchLike('  senior developer  '))->toBe('%senior%developer%');
    });

    it('replaces one or more whitespace characters with wildcards', function () {
        expect(searchLike("senior\tphp\nlaravel"))->toBe('%senior%php%laravel%');
    });

    it('casts scalar values before building the search pattern', function () {
        expect(searchLike(123))->toBe('%123%');
    });
});

describe('isAjax', function () {
    it('detects xml http requests', function () {
        app()->instance('request', Request::create('/helper-test', 'GET', server: [
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
        ]));

        expect(isAjax())->toBeTrue();
    });

    it('detects requests that expect json responses', function () {
        app()->instance('request', Request::create('/helper-test', 'GET', server: [
            'HTTP_ACCEPT' => 'application/json',
        ]));

        expect(isAjax())->toBeTrue();
    });

    it('returns false for normal html requests', function () {
        app()->instance('request', Request::create('/helper-test'));

        expect(isAjax())->toBeFalse();
    });
});

describe('notification helpers', function () {
    it('resolves a notification view from a known notification type string', function () {
        expect(notification_type_view(ApplicationStatusUpdated::class))
            ->toBe('notifications.types.application-status-updated');
    });

    it('resolves a notification view from a database notification model', function () {
        $notification = DatabaseNotification::make([
            'type' => ApplicationStatusUpdated::class,
            'data' => [],
        ]);

        expect(notification_type_view($notification))
            ->toBe('notifications.types.application-status-updated');
    });

    it('falls back to the default notification view for unknown types', function () {
        expect(notification_type_view('unknown-notification-type'))
            ->toBe('notifications.types.default');
    });

    it('uses the notification data url as the redirect target when present', function () {
        $notification = DatabaseNotification::make([
            'type' => ApplicationStatusUpdated::class,
            'data' => ['url' => '/applications/123'],
        ]);

        expect(notification_redirect_url($notification))->toBe('/applications/123');
    });

    it('falls back to the dashboard route when no redirect url is present', function () {
        $notification = DatabaseNotification::make([
            'type' => ApplicationStatusUpdated::class,
            'data' => [],
        ]);

        expect(notification_redirect_url($notification))->toBe(route('dashboard'));
    });
});

describe('skillsMatchScore', function () {
    it('returns zero when there are no job skills to compare against', function () {
        expect(skillsMatchScore(['php', 'laravel'], []))->toBe(0.0);
    });

    it('returns zero when no user skills match the job skills', function () {
        expect(skillsMatchScore(['php', 'laravel'], ['react', 'vue']))->toBe(0.0);
    });

    it('returns the percentage of required job skills matched by the user', function () {
        expect(skillsMatchScore(['php', 'laravel'], ['php', 'laravel', 'mysql', 'redis']))
            ->toBe(50.0);
    });

    it('rounds the score to two decimal places', function () {
        expect(skillsMatchScore(['php'], ['php', 'laravel', 'mysql']))
            ->toBe(33.33);
    });
});
