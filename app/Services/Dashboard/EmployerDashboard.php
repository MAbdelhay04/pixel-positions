<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Enums\JobStatus;
use App\Models\User;

class EmployerDashboard implements DashboardInterface
{
    public function render(User $user)
    {
        $jobs = $user->jobs()
            ->with(['employer', 'skills', 'tags', 'category'])
            ->withCount('applications')
            ->latest()
            ->paginate();

        $baseQuery = $user->jobs();

        return view('dashboard.employer', [
            'jobs' => $jobs,
            'stats' => [
                'total' => $baseQuery->count(),
                'open' => $baseQuery->where('status', JobStatus::Open)->count(),
                'applications' => $baseQuery->withCount('applications')->get()->sum('applications_count'),
            ]
        ]);
    }
}
