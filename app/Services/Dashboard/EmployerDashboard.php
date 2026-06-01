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
            ->when(request('q'), fn($q, $v) => $q->where('title', 'like', searchLike($v)))
            ->when(request('type'), fn($q, $v) => $q->whereIn('type', (array) $v))
            ->when(request('location'), fn($q, $v) => $q->whereIn('location', (array) $v))
            ->when(request('status'), fn($q, $v) => $q->whereIn('status', (array) $v))
            ->latest()
            ->paginate(9)
            ->withQueryString();

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
