<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Models\JobListing;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class JobListingService
{
    public function baseQuery(Builder|Relation $query, bool $useStatusFilter = false): Builder
    {
        if ($query instanceof Relation) {
            $query = $query->getQuery();
        }

        return $query
            ->with(['employer', 'skills', 'tags', 'category'])
            ->withCount('applications')
            ->when(
                Auth::check() && Auth::user()->isCandidate(),
                fn ($q) => $q->with(['applications' => fn ($q) => $q->where('candidate_id', Auth::id())])
            )
            ->when(request('q'), fn ($q, $v) => $q->where('title', 'like', searchLike($v)))
            ->when(request('type'), fn ($q, $v) => $q->whereIn('type', (array) $v))
            ->when(request('location'), fn ($q, $v) => $q->whereIn('location', (array) $v))
            ->when(
                $useStatusFilter,
                fn ($q) => $q->when(request('status'), fn ($q, $v) => $q->whereIn('status', (array) $v)),
                fn ($q) => $q->where('status', JobStatus::Open)
            )
            ->latest();
    }

    public function paginate(Builder $query, int $perPage = 12)
    {
        return $query->paginate($perPage)->withQueryString();
    }

    public function all(int $perPage = 12, bool $useStatusFilter = false)
    {
        return $this->paginate(
            $this->baseQuery(JobListing::query(), $useStatusFilter),
            $perPage
        );
    }

    public function byTag(Tag $tag, int $perPage = 12, bool $useStatusFilter = false)
    {
        return $this->paginate(
            $this->baseQuery($tag->jobs(), $useStatusFilter),
            $perPage
        );
    }

    public function byEmployer(User $employer, int $perPage = 12, bool $useStatusFilter = false)
    {
        return $this->paginate(
            $this->baseQuery($employer->jobs(), $useStatusFilter),
            $perPage
        );
    }
}
