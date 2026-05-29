<?php

use App\Enums\UserRole;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/jobs');
Route::get('jobs', [JobListingController::class, 'index'])->name('jobs.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('jobs', JobListingController::class)
        ->except(['index', 'show'])
        ->middleware('role:' . UserRole::Employer->value);

    Route::get('jobs/{job}', [JobListingController::class, 'show'])->name('jobs.show');

    Route::resource('applications', ApplicationController::class)
        ->middleware('role:' .  UserRole::Candidate->value);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
