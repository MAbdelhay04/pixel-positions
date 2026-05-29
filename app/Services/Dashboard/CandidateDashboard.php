<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Models\User;

class CandidateDashboard implements DashboardInterface
{
    public function render(User $user)
    {
        return view('dashboard.candidate');
    }
}
