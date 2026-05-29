<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Http\Requests\StoreJobListingRequest;
use App\Http\Requests\UpdateJobListingRequest;
use App\Models\JobListing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class JobListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = JobListing::with(['employer', 'skills', 'tags', 'category'])
            ->withCount('applications')
            ->latest()
            ->paginate();

        return view('jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', JobListing::class);
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobListingRequest $request)
    {
        $jobData = $request->validated();

        $job = Auth::user()->jobs()->create($jobData);

        if ($request->input('skills')) {
            $job->syncSkills(explode(',', $request->input('skills')));
        }

        if ($request->input('tags')) {
            $job->syncTags(explode(',', $request->input('tags')));
        }

        return redirect(route('jobs.show', $job));
    }

    /**
     * Display the specified resource.
     */
    public function show(JobListing $job)
    {
        return view('jobs.show', ['job' => $job]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobListing $job)
    {
        Gate::authorize('update', $job);
        return view('jobs.edit', ['job' => $job]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobListingRequest $request, JobListing $job)
    {
        $jobData = $request->validated();

        $job->update($jobData);

        $job->syncTags($request->input('tags') ? explode(',', $request->input('tags')) : []);
        $job->syncSkills($request->input('skills') ? explode(',', $request->input('skills')) : []);

        return redirect(route('jobs.show', $job));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobListing $job)
    {
        Gate::authorize('delete', $job);
        $job->delete();
        return redirect(route('jobs.index'));
    }
}
