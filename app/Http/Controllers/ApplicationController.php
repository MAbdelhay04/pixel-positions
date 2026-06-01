<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(JobListing $job)
    {
        Gate::authorize('viewApplications', $job);

        $applications = $job->applications()
            ->with('candidate')
            ->when(request('q'), fn($q, $v) => $q->whereHas('candidate', fn($q) => $q->where('name', 'like', searchLike($v))))
            ->when(request('status'), fn($q) => $q->whereIn('status', (array) request('status')))
            ->when(request('date_from'), fn($q) => $q->whereDate('created_at', '>=', request('date_from')))
            ->when(request('date_to'), fn($q) => $q->whereDate('created_at', '<=', request('date_to')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        if (isAjax()) {
            return view('applications._results', compact('applications'));
        }

        $stats = [
            'total'      => $job->applications()->count(),
            'reviewing'  => $job->applications()->where('status', ApplicationStatus::Reviewing)->count(),
            'interviews' => $job->applications()->where('status', ApplicationStatus::Interview)->count(),
        ];

        return view('applications.index', compact('job', 'applications', 'stats'));
    }

    public function store(StoreApplicationRequest $request, JobListing $job)
    {
        $resume = $request->file('resume')->store('resumes', 'local');

        $application = $job->applications()->create([
            'candidate_id' => Auth::id(),
            'resume'       => $resume,
            'cover_letter' => $request->input('cover_letter'),
        ]);

        $application->statusLogs()->create([
            'status'     => ApplicationStatus::Submitted,
            'changed_by' => Auth::id(),
        ]);

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', __('Applied to “:title” successfully.', ['title' => $job->title]));
    }

    public function show(Application $application)
    {
        Gate::authorize('view', $application);
        $application->load(['candidate', 'job.employer', 'statusLogs.changer']);

        return view('applications.show', compact('application'));
    }

    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $status = $request->enum('status', ApplicationStatus::class);

        $application->update(['status' => $status]);

        $application->statusLogs()->create([
            'status'     => $status,
            'changed_by' => Auth::id(),
        ]);

        return back()->with('success', __('Application marked as :status.', [
            'status' => $status->label(),
        ]));
    }

    public function resume(Application $application)
    {
        Gate::authorize('view', $application);

        $extension = pathinfo($application->resume, PATHINFO_EXTENSION);
        $filename  = $application->candidate->name . '_resume.' . $extension;

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        return $disk->download($application->resume, $filename);
    }
}
