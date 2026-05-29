<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Enums\UserRole;
use App\Models\User;

class DashboardFactory
{
    public static function make(User $user): DashboardInterface
    {
        return match ($user->role) {
            UserRole::Employer => new EmployerDashboard(),
            UserRole::Candidate => new CandidateDashboard(),
        };
    }
}
