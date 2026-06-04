<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCandidateProfileRequest;
use App\Http\Requests\UpdateEmployerProfileRequest;
use App\Models\User;
use App\Services\ImageOptimizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    public function candidate(Request $request): View
    {
        Gate::authorize('view-candidate-profile');

        return view('users.candidate.show', [
            'user' => $request->user()->load('skills'),
            'profile' => $request->user()->profile_data ?? [],
            'canEdit' => true,
        ]);
    }

    public function showCandidate(User $candidate): View
    {
        Gate::authorize('view-candidate-profile-for-employer', $candidate);

        $candidate->load('skills');

        return view('users.candidate.show', [
            'user' => $candidate,
            'profile' => $candidate->profile_data ?? [],
            'canEdit' => Auth::user()?->is($candidate),
        ]);
    }

    public function editCandidate(Request $request): View
    {
        Gate::authorize('update-candidate-profile');

        return view('users.candidate.edit', [
            'user' => $request->user(),
            'profile' => $request->user()->profile_data ?? [],
        ]);
    }

    public function updateCandidate(UpdateCandidateProfileRequest $request): RedirectResponse
    {
        $request->user()->forceFill([
            'profile_data' => $request->profileData(),
        ])->save();

        return Redirect::route('users.candidate.show')->with('status', 'candidate-profile-updated');
    }

    public function employer(Request $request): View
    {
        Gate::authorize('view-employer-profile');

        return view('users.employer.show', [
            'user' => $request->user(),
            'profile' => $request->user()->profile_data ?? [],
            'canEdit' => true,
        ]);
    }

    public function showEmployer(User $employer): View
    {
        abort_unless($employer->isEmployer(), 404);

        return view('users.employer.show', [
            'user' => $employer,
            'profile' => $employer->profile_data ?? [],
            'canEdit' => Auth::user()?->is($employer),
        ]);
    }

    public function editEmployer(Request $request): View
    {
        Gate::authorize('update-employer-profile');

        return view('users.employer.edit', [
            'user' => $request->user(),
            'profile' => $request->user()->profile_data ?? [],
        ]);
    }

    public function companySettings(Request $request): View
    {
        Gate::authorize('update-employer-profile');

        return view('users.employer.settings', [
            'user' => $request->user(),
            'profile' => $request->user()->profile_data ?? [],
        ]);
    }

    public function updateEmployer(
        UpdateEmployerProfileRequest $request,
        ImageOptimizationService $imageOptimizationService
    ): RedirectResponse {
        $user = $request->user();

        $user->name = $request->validated('company_name');
        $user->profile_data = $request->profileData();

        if ($request->hasFile('logo')) {
            if ($user->logo) {
                Storage::disk('public')->delete($user->logo);
            }

            $user->logo = $imageOptimizationService->store($request->file('logo'), width: 300);
        }

        $user->save();

        return Redirect::route('users.employer.show')->with('status', 'employer-profile-updated');
    }
}
