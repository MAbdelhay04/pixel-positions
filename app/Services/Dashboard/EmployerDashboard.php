<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Enums\JobStatus;
use App\Models\User;
use App\Services\JobListingService;

class EmployerDashboard implements DashboardInterface
{
    public function __construct(private JobListingService $service) {}
    public function render(User $user)
    {
        $jobs = $this->service->byEmployer(employer: $user, useStatusFilter: true);

        if (isAjax()) {
            return view('dashboard._employer_results', compact('jobs'));
        }

        $stats = [
            'total'        => $user->jobs()->count(),
            'open'         => $user->jobs()->where('status', JobStatus::Open)->count(),
            'applications' => $user->jobs()->withCount('applications')->get()->sum('applications_count'),
        ];

        return view('dashboard.employer', compact('jobs', 'stats'));
    }
}
