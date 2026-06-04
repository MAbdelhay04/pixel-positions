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
            ->with(['job.employer', 'job.skills', 'job.tags', 'job.category'])
            ->when(request('q'), fn($q, $v) => $q->whereHas('job', fn($q) => $q->where('title', 'like', searchLike($v))))
            ->when(request('status'), fn($q, $v) => $q->whereIn('status', (array) $v))
            ->when(request('type'), fn($q, $v) => $q->whereHas('job', fn($q) => $q->whereIn('type', (array) $v)))
            ->when(request('location'), fn($q, $v) => $q->whereHas('job', fn($q) => $q->whereIn('location', (array) $v)))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        if (isAjax()) {
            return view('dashboard._candidate_results', compact('applications'));
        }

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
