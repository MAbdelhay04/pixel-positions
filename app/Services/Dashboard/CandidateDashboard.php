<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CandidateDashboard implements DashboardInterface
{
    public function render(User $user)
    {
        $applications = Auth::user()
            ->applications()
            ->with(['job', 'employer'])
            ->latest()
            ->paginate(9);

        $stats = [
            'total'      => $applications->total(),
            'pending'    => Auth::user()->applications()
                ->whereIn('status', ['submitted', 'reviewing'])
                ->count(),
            'interviews' => Auth::user()->applications()
                ->where('status', 'interview')
                ->count(),
        ];

        return view('dashboard.candidate', compact('applications', 'stats'));
    }
}
