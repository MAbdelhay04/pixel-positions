<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\JobListingService;
use Illuminate\Support\Facades\App;

class DashboardFactory
{
    public static function make(User $user): DashboardInterface
    {
        return match ($user->role) {
            UserRole::Employer => new EmployerDashboard(App::make(JobListingService::class)),
            UserRole::Candidate => new CandidateDashboard,
        };
    }
}
