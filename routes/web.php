<?php

use App\Enums\UserRole;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public
Route::redirect('/', '/jobs');
Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/tags/{tag}/jobs', [JobListingController::class, 'indexByTag'])->name('jobs.index_tag');
Route::get('/companies/{employer}', [UserController::class, 'showEmployer'])->name('companies.show');
Route::get('/companies/{employer}/jobs', [JobListingController::class, 'indexByEmployer'])->name('companies.jobs');

// Authenticated
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/dropdown', [NotificationController::class, 'dropdown'])->name('notifications.dropdown');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

    // Employer only
    Route::middleware('role:' . UserRole::Employer->value)->group(function () {
        Route::resource('jobs', JobListingController::class)->except(['index', 'show']);
        Route::patch('/jobs/{job}/status', [JobListingController::class, 'updateStatus'])->name('jobs.update_status');
        Route::get('/jobs/{job}/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
        Route::get('/applications/{application}/resume', [ApplicationController::class, 'resume'])->name('applications.resume');
    });

    // Candidate only
    Route::middleware('role:' . UserRole::Candidate->value)->group(function () {
        Route::post('/jobs/{job}/applications', [ApplicationController::class, 'store'])->name('applications.store');
    });
});

// Public
Route::get('/jobs/{job}', [JobListingController::class, 'show'])->name('jobs.show');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/logo', [ProfileController::class, 'updateLogo'])->name('profile.logo');
    Route::patch('/profile/skills', [ProfileController::class, 'updateSkills'])->name('profile.skills');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/candidate/profile', [UserController::class, 'candidate'])->name('users.candidate.show');
    Route::get('/candidate/profile/edit', [UserController::class, 'editCandidate'])->name('users.candidate.edit');
    Route::patch('/candidate/profile', [UserController::class, 'updateCandidate'])->name('users.candidate.update');

    Route::get('/employer/profile', [UserController::class, 'employer'])->name('users.employer.show');
    Route::get('/employer/profile/edit', [UserController::class, 'editEmployer'])->name('users.employer.edit');
    Route::patch('/employer/profile', [UserController::class, 'updateEmployer'])->name('users.employer.update');
    Route::get('/company/settings', [UserController::class, 'companySettings'])->name('users.employer.settings');

    Route::get('/candidates/{candidate}', [UserController::class, 'showCandidate'])->name('candidates.show');
});

require __DIR__ . '/auth.php';
