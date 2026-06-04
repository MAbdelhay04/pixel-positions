<?php

use App\Enums\UserRole;
use App\Models\User;

it('fails to unauthenticated user', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login'));
});

it('shows candidate dashboard for a candidate user', function () {
    $user = User::factory()->create(['role' => UserRole::Candidate]);
    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertViewIs('dashboard.candidate');
});

it('shows candidate results for a candidate user with Ajax Request', function () {
    $user = User::factory()->create(['role' => UserRole::Candidate]);
    $response = $this->actingAs($user)->get('/dashboard', [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertViewIs('dashboard._candidate_results');
});

it('shows employer dashboard for a employer user', function () {
    $user = User::factory()->create(['role' => UserRole::Employer]);
    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertViewIs('dashboard.employer');
});

it('shows employer results for a employer user with Ajax Request', function () {
    $user = User::factory()->create(['role' => UserRole::Employer]);
    $response = $this->actingAs($user)->get('/dashboard', [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertViewIs('dashboard._employer_results');
});
