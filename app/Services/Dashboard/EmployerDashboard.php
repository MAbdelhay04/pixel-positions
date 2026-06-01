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
            ->paginate(9);

        $stats = [
            'total'        => $user->jobs()->count(),
            'open'         => $user->jobs()->where('status', JobStatus::Open)->count(),
            'applications' => $user->jobs()->withCount('applications')->get()->sum('applications_count'),
        ];

        return view('dashboard.employer', compact('jobs', 'stats'));
    }
}
