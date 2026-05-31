<?php

namespace App\Policies;

use App\Enums\JobStatus;
use App\Enums\UserRole;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobListingPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::Employer;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobListing $jobListing): bool
    {
        return $user->id === $jobListing->employer_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobListing $jobListing): bool
    {
        return $user->id === $jobListing->employer_id;
    }

    public function apply(User $user, JobListing $jobListing): bool
    {
        return $user->role === UserRole::Candidate
            && $jobListing->status === JobStatus::Open
            && !$jobListing->applications()->where('candidate_id', $user->id)->exists();
    }
}
