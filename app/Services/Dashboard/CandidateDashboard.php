<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Enums\ApplicationStatus;
use App\Models\User;

class CandidateDashboard implements DashboardInterface
{
    public function render(User $user)
    {
        $applications = $user->applications()
            ->with(['job', 'employer'])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $stats = [
            'total'      => $user->applications()->count(),
            'pending'    => $user->applications()
                ->whereIn('status', [ApplicationStatus::Submitted, ApplicationStatus::Reviewing])
                ->count(),
            'interviews' => $user->applications()
                ->where('status', ApplicationStatus::Interview)
                ->count(),
        ];

        return view('dashboard.candidate', compact('applications', 'stats'));
    }
}
