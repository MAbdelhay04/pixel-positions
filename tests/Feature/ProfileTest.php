<?php

use App\Enums\UserRole;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('profile page', function () {
    test('is displayed', function () {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    });
});

describe('profile information', function () {
    test('can be updated', function () {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    });

    test('email verification status is unchanged when the email address is unchanged', function () {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    });
});

describe('profile photo', function () {
    test('can be updated', function () {
        fakePublicStorage();

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('logo.png', 500, 500);

        $response = $this
            ->actingAs($user)
            ->post(route('profile.logo'), ['logo' => $file]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        expect($user->logo)->not->toBeNull()
            ->and($user->logo)->toStartWith('logos/')
            ->and($user->logo)->toEndWith('.webp');

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($user->logo);
    });
});

describe('profile skills', function () {
    test('candidate can update skills', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $response = $this
            ->actingAs($user)
            ->patch(route('profile.skills'), [
                'skills' => ['php', 'laravel', 'testing'],
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        expect($user->skills()->pluck('name')->all())->toBe([
            'php',
            'laravel',
            'testing',
        ]);
    });

    test('candidate skills are normalized and duplicated skills are ignored', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        Skill::create(['name' => 'php']);

        $response = $this
            ->actingAs($user)
            ->patch(route('profile.skills'), [
                'skills' => [' PHP ', 'Laravel', 'php', ' Testing '],
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        expect($user->skills()->pluck('name')->all())->toBe([
            'php',
            'laravel',
            'testing',
        ]);

        $this->assertDatabaseCount('skills', 3);
    });

    test('candidate can clear skills', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $user->syncSkills(['php', 'laravel']);

        $response = $this
            ->actingAs($user)
            ->patch(route('profile.skills'));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        expect($user->skills()->count())->toBe(0);
    });

    test('employer cannot update skills', function () {
        $user = User::factory()->create(['role' => UserRole::Employer]);

        $response = $this
            ->actingAs($user)
            ->patch(route('profile.skills'), [
                'skills' => ['php', 'laravel'],
            ]);

        $response->assertForbidden();

        expect($user->skills()->count())->toBe(0);
    });

    test('skills must be an array', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->patch(route('profile.skills'), [
                'skills' => 'php',
            ]);

        $response
            ->assertSessionHasErrors('skills')
            ->assertRedirect('/profile');

        expect($user->skills()->count())->toBe(0);
    });

    test('candidate cannot add more than fifteen skills', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->patch(route('profile.skills'), [
                'skills' => collect(range(1, 16))
                    ->map(fn (int $number) => "skill-{$number}")
                    ->all(),
            ]);

        $response
            ->assertSessionHasErrors('skills')
            ->assertRedirect('/profile');

        expect($user->skills()->count())->toBe(0);
    });

    test('skill names must be valid strings', function (array $skills, string $errorKey) {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->patch(route('profile.skills'), [
                'skills' => $skills,
            ]);

        $response
            ->assertSessionHasErrors($errorKey)
            ->assertRedirect('/profile');

        expect($user->skills()->count())->toBe(0);
    })->with([
        'too short' => [['p'], 'skills.0'],
        'too long' => [[str_repeat('a', 31)], 'skills.0'],
        'not a string' => [[[1, 2, 3]], 'skills.0'],
    ]);
});

describe('profile deletion', function () {
    test('user can delete their account', function () {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    });

    test('correct password must be provided to delete account', function () {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    });
});
