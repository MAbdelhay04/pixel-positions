<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobListingRequest;
use App\Http\Requests\UpdateJobListingRequest;
use App\Http\Requests\UpdateJobListingStatusRequest;
use App\Models\JobListing;
use App\Models\Tag;
use App\Models\User;
use App\Services\JobListingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class JobListingController extends Controller
{
    public function __construct(private JobListingService $service) {}

    public function index()
    {
        $jobs = $this->service->all();

        return isAjax()
            ? view('jobs._results', compact('jobs'))
            : view('jobs.index', compact('jobs'));
    }

    public function indexByTag(Tag $tag)
    {
        $jobs = $this->service->byTag($tag);

        return isAjax()
            ? view('jobs._results', compact('jobs'))
            : view('jobs.index', compact('jobs', 'tag'));
    }

    public function indexByEmployer(User $employer)
    {
        abort_unless($employer->isEmployer(), 404);

        $jobs = $this->service->byEmployer($employer);

        return isAjax()
            ? view('jobs._results', compact('jobs'))
            : view('jobs.index', compact('jobs', 'employer'));
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

        $job->syncTags($request->validated('tags', []));
        $job->syncSkills($request->validated('skills', []));

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

        $job->syncTags($request->validated('tags', []));
        $job->syncSkills($request->validated('skills', []));

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', __('Job “:title” updated successfully.', ['title' => $job->title]));
    }

    public function updateStatus(UpdateJobListingStatusRequest $request, JobListing $job)
    {
        $job->update(['status' => $request->validated('status')]);

        return back()->with('success', __('Job “:title” status updated to :status.', [
            'title'  => $job->title,
            'status' => $job->refresh()->status->label(),
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
