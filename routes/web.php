<?php

use App\Enums\UserRole;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/jobs');

// Public
Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobListingController::class, 'show'])->name('jobs.show');
Route::get('/tags/{tag}/jobs', [JobListingController::class, 'indexByTag'])->name('jobs.index_tag');

// Authenticated
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employer only
    Route::middleware('role:' . UserRole::Employer->value)->group(function () {
        Route::resource('jobs', JobListingController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
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

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
