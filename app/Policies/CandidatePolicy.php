<?php

namespace App\Policies;

use App\Models\User;

class CandidatePolicy
{
    public function view(User $user): bool
    {
        return $user->isCandidate();
    }

    public function update(User $user): bool
    {
        return $user->isCandidate();
    }

    public function viewForEmployer(User $user, User $candidate): bool
    {
        if (! $candidate->isCandidate()) {
            return false;
        }

        if ($user->isCandidate()) {
            return $user->is($candidate);
        }

        return $user->isEmployer()
            && $candidate->applications()
                ->whereHas('job', fn ($query) => $query->where('employer_id', $user->id))
                ->exists();
    }
}
