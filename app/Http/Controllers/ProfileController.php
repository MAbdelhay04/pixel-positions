<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateProfileSkillsRequest;
use App\Services\ImageOptimizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user()->load('skills'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile photo.
     */
    public function updateLogo(Request $request, ImageOptimizationService $imageOptimizationService): RedirectResponse
    {
        $request->validateWithBag('updateLogo', [
            'logo' => ['required', File::image(true), 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->logo) {
            Storage::disk('public')->delete($user->logo);
        }

        $user->logo = $imageOptimizationService->store($request->file('logo'), width: 300);

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'logo-updated');
    }

    /**
     * Update the user's skills.
     */
    public function updateSkills(UpdateProfileSkillsRequest $request): RedirectResponse
    {
        $request->user()->syncSkills($request->validated('skills', []));

        return Redirect::route('profile.edit')->with('status', 'skills-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
