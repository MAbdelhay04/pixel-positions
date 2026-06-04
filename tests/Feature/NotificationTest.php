<?php

use App\Models\Application;
use App\Models\User;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Notifications\DatabaseNotification;

describe('notification index requests', function () {
    it('redirects unauthenticated users to login', function () {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect(route('login'));
    });

    it('shows the notifications index for authenticated users', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('notifications.index'));

        $response->assertViewIs('notifications.index');
    });
});

describe('notification dropdown requests', function () {
    it('redirects unauthenticated users to login', function () {
        $response = $this->get(route('notifications.dropdown'));

        $response->assertRedirect(route('login'));
    });

    it('returns not found for non-ajax requests', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('notifications.dropdown'));

        $response->assertNotFound();
    });

    it('shows the dropdown partial for ajax requests', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('notifications.dropdown'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertViewIs('notifications._dropdown');
    });
});

describe('notification mark as read requests', function () {
    it('redirects unauthenticated users to login', function () {
        $notification = DatabaseNotification::create([
            'id' => (string) str()->uuid(),
            'type' => ApplicationStatusUpdated::class,
            'notifiable_type' => User::class,
            'notifiable_id' => User::factory()->create()->id,
            'data' => [],
        ]);

        $response = $this->patch(route('notifications.read', $notification->id));

        $response->assertRedirect(route('login'));
    });

    it('marks a notification as read and returns json', function () {
        $user = User::factory()->create(['role' => 'candidate']);
        $application = Application::factory()->forCandidate($user)->create();
        $job = $application->job;

        $user->notify(new ApplicationStatusUpdated(
            application: $application,
            jobTitle: $job->title,
            statusLabel: 'Reviewing',
            statusValue: 'reviewing',
        ));

        $notification = $user->unreadNotifications()->first();

        $response = $this->actingAs($user)->patchJson(route('notifications.read', $notification->id));

        $response
            ->assertOk()
            ->assertJson([
                'unread_count' => 0,
                'redirect_url' => route('dashboard'),
            ]);

        expect($notification->fresh()->read_at)->not->toBeNull();
    });

    it('does not change read_at when the notification is already read', function () {
        $user = User::factory()->create(['role' => 'candidate']);
        $application = Application::factory()->forCandidate($user)->create();

        $user->notify(new ApplicationStatusUpdated(
            application: $application,
            jobTitle: $application->job->title,
            statusLabel: 'Reviewing',
            statusValue: 'reviewing',
        ));

        $notification = $user->unreadNotifications()->first();
        $notification->markAsRead();
        $readAt = $notification->fresh()->read_at;

        $response = $this->actingAs($user)->patchJson(route('notifications.read', $notification->id));

        $response->assertOk()->assertJson(['unread_count' => 0]);

        expect($notification->fresh()->read_at->timestamp)->toBe($readAt->timestamp);
    });

    it('returns not found for another users notification', function () {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $owner->notify(new ApplicationStatusUpdated(
            application: Application::factory()->forCandidate($owner)->create(),
            jobTitle: 'Test Job',
            statusLabel: 'Reviewing',
            statusValue: 'reviewing',
        ));

        $notification = $owner->unreadNotifications()->first();

        $response = $this->actingAs($other)->patchJson(route('notifications.read', $notification->id));

        $response->assertNotFound();
    });
});

describe('notification mark all as read requests', function () {
    it('redirects unauthenticated users to login', function () {
        $response = $this->post(route('notifications.read_all'));

        $response->assertRedirect(route('login'));
    });

    it('marks all notifications as read and returns json', function () {
        $user = User::factory()->create(['role' => 'candidate']);
        $application = Application::factory()->forCandidate($user)->create();

        $user->notify(new ApplicationStatusUpdated(
            application: $application,
            jobTitle: $application->job->title,
            statusLabel: 'Reviewing',
            statusValue: 'reviewing',
        ));

        $user->notify(new ApplicationStatusUpdated(
            application: $application,
            jobTitle: $application->job->title,
            statusLabel: 'Interview',
            statusValue: 'interview',
        ));

        $response = $this->actingAs($user)->postJson(route('notifications.read_all'));

        $response
            ->assertOk()
            ->assertJson(['unread_count' => 0]);

        expect($user->unreadNotifications()->count())->toBe(0);
    });
});
