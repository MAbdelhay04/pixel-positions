<?php

use App\Enums\UserRole;
use Illuminate\Http\UploadedFile;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    fakePublicStorage();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'username' => 'example_user',
        'logo' => UploadedFile::fake()->image('logo.png'),
        'role' => UserRole::Candidate->value,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
