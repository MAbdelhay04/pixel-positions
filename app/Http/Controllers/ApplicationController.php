<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        //
    }

    public function store(StoreApplicationRequest $request, JobListing $job)
    {
        $resume = $request->file('resume')->store('resumes', 'local');

        $job->applications()->create([
            'candidate_id' => Auth::id(),
            'resume'       => $resume,
            'cover_letter' => $request->input('cover_letter'),
        ]);

        return back()->with('success', __('Applied to :title successfully.', ['title' => $job->title]));
    }

    public function show(Application $application)
    {
        //
    }

    public function update(UpdateApplicationRequest $request, Application $application) {}

    public function destroy(Application $application) {}
}
