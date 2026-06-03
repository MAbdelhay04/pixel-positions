<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Http\Requests\StoreJobListingRequest;
use App\Http\Requests\UpdateJobListingRequest;
use App\Http\Requests\UpdateJobListingStatusRequest;
use App\Models\JobListing;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class JobListingController extends Controller
{
    public function index()
    {
        $jobs = JobListing::with(['employer', 'skills', 'tags', 'category'])
            ->withCount('applications')
            ->when(
                Auth::check() && Auth::user()->isCandidate(),
                fn($q) => $q->with(['applications' => fn($q) => $q->where('candidate_id', Auth::id())])
            )
            ->when(request('q'), fn($q, $v) => $q->where('title', 'like', searchLike($v)))
            ->when(request('type'), fn($q, $v) => $q->whereIn('type', (array) $v))
            ->when(request('location'), fn($q, $v) => $q->whereIn('location', (array) $v))
            ->where('status', JobStatus::Open)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        if (isAjax()) {
            return view('jobs._results', compact('jobs'));
        }

        return view('jobs.index', compact('jobs'));
    }

    public function indexByTag(Tag $tag)
    {
        $jobs = $tag->jobs()
            ->with(['employer', 'skills', 'tags', 'category'])
            ->withCount('applications')
            ->when(
                Auth::check() && Auth::user()->isCandidate(),
                fn($q) => $q->with(['applications' => fn($q) => $q->where('candidate_id', Auth::id())])
            )
            ->when(request('q'), fn($q, $v) => $q->where('title', 'like', searchLike($v)))
            ->when(request('type'), fn($q, $v) => $q->whereIn('type', (array) $v))
            ->when(request('location'), fn($q, $v) => $q->whereIn('location', (array) $v))
            ->where('status', JobStatus::Open)
            ->latest()
            ->paginate()
            ->withQueryString();

        if (isAjax()) {
            return view('jobs._results', compact('jobs'));
        }

        return view('jobs.index', compact('jobs', 'tag'));
    }

    public function create()
    {
        Gate::authorize('create', JobListing::class);
        return view('jobs.create');
    }

    public function store(StoreJobListingRequest $request)
    {
        $jobData = $request->validatedJobData();

        $job = Auth::user()->jobs()->create($jobData);

        if ($request->validated('skills')) {
            $job->syncSkills($request->validated('skills'));
        }

        if ($request->validated('tags')) {
            $job->syncTags($request->validated('tags'));
        }

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', __('Job “:title” created successfully.', ['title' => $job->title]));
    }

    public function show(JobListing $job)
    {
        return view('jobs.show', ['job' => $job]);
    }

    public function edit(JobListing $job)
    {
        Gate::authorize('update', $job);
        return view('jobs.edit', ['job' => $job]);
    }

    public function update(UpdateJobListingRequest $request, JobListing $job)
    {
        $jobData = $request->validatedJobData();

        $job->update($jobData);

        $job->syncTags($request->validated('tags') ? $request->validated('tags') : []);
        $job->syncSkills($request->validated('skills') ? $request->validated('skills') : []);

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', __('Job “:title” updated successfully.', ['title' => $job->title]));
    }

    public function updateStatus(UpdateJobListingStatusRequest $request, JobListing $job)
    {
        $status = $request->enum('status', JobStatus::class);

        $job->update(['status' => $status]);

        return back()->with('success', __('Job “:title” status updated to :status.', [
            'title'  => $job->title,
            'status' => $status->label(),
        ]));
    }

    public function destroy(JobListing $job)
    {
        Gate::authorize('delete', $job);

        $title = $job->title;
        $job->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', __('Job “:title” deleted successfully.', ['title' => $title]));
    }
}
