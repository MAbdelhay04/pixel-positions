<?php

use App\Enums\ApplicationStatus;
use App\Enums\JobStatus;
use App\Mail\ApplicationSubmittedToCandidate;
use App\Mail\NewApplicationForEmployer;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

describe('application index requests', function () {
    it('redirects unauthenticated users to login', function () {
        $job = JobListing::factory()->create();

        $response = $this->get(route('applications.index', $job));

        $response->assertRedirect(route('login'));
    });

    it('does not show applications for a candidate user', function () {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($candidate)->get(route('applications.index', $job));

        $response->assertForbidden();
    });

    it('does not show applications for a different employer', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($employer)->get(route('applications.index', $job));

        $response->assertForbidden();
    });

    it('shows the applications index for the job owner', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->forEmployer($employer)->create();

        $response = $this->actingAs($employer)->get(route('applications.index', $job));

        $response->assertViewIs('applications.index');
    });

    it('shows the applications results partial for ajax requests', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->forEmployer($employer)->create();

        $response = $this->actingAs($employer)->get(route('applications.index', $job), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertViewIs('applications._results');
    });
});

describe('application store requests', function () {
    beforeEach(function () {
        Mail::fake();
        fakeStorage('local');
    });

    it('redirects unauthenticated users to login', function () {
        $job = JobListing::factory()->create(['status' => JobStatus::Open->value]);

        $response = $this->post(route('applications.store', $job));

        $response->assertRedirect(route('login'));
    });

    it('does not store an application for an employer user', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->forEmployer($employer)->create(['status' => JobStatus::Open->value]);

        $response = $this->actingAs($employer)->post(route('applications.store', $job), [
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ]);

        $response->assertForbidden();
    });

    it('does not store an application when the job is not open', function () {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create(['status' => JobStatus::Draft->value]);

        $response = $this->actingAs($candidate)->post(route('applications.store', $job), [
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ]);

        $response->assertForbidden();
    });

    it('does not store a duplicate application from the same candidate', function () {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create(['status' => JobStatus::Open->value]);
        Application::factory()->forJob($job)->forCandidate($candidate)->create();

        $response = $this->actingAs($candidate)->post(route('applications.store', $job), [
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ]);

        $response->assertForbidden();
    });

    it('stores an application for an eligible candidate', function () {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create(['status' => JobStatus::Open->value]);

        $response = $this->actingAs($candidate)->post(route('applications.store', $job), [
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
            'cover_letter' => 'I am excited to apply.',
        ]);

        $this->assertDatabaseHas('applications', [
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'cover_letter' => 'I am excited to apply.',
        ]);

        $response->assertRedirect(route('jobs.show', $job));

        Mail::assertQueued(ApplicationSubmittedToCandidate::class);
        Mail::assertQueued(NewApplicationForEmployer::class);
    });

    it('fails to store an application with invalid data', function () {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create(['status' => JobStatus::Open->value]);

        $response = $this->actingAs($candidate)->post(route('applications.store', $job), [
            'resume' => UploadedFile::fake()->create('resume.txt', 100, 'text/plain'),
        ]);

        $response->assertSessionHasErrors('resume', errorBag: 'applyJob');
    });
});

describe('application show requests', function () {
    it('redirects unauthenticated users to login', function () {
        $application = Application::factory()->create();

        $response = $this->get(route('applications.show', $application));

        $response->assertRedirect(route('login'));
    });

    it('does not show an application for a candidate user', function () {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $application = Application::factory()->forCandidate($candidate)->create();

        $response = $this->actingAs($candidate)->get(route('applications.show', $application));

        $response->assertForbidden();
    });

    it('does not show an application for a different employer', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $application = Application::factory()->create();

        $response = $this->actingAs($employer)->get(route('applications.show', $application));

        $response->assertForbidden();
    });

    it('shows an application for the job owner', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->forEmployer($employer)->create();
        $application = Application::factory()->forJob($job)->create();

        $response = $this->actingAs($employer)->get(route('applications.show', $application));

        $response->assertViewIs('applications.show');
    });
});

describe('application update requests', function () {
    beforeEach(function () {
        Notification::fake();
    });

    it('redirects unauthenticated users to login', function () {
        $application = Application::factory()->create();

        $response = $this->patch(route('applications.update', $application));

        $response->assertRedirect(route('login'));
    });

    it('does not update an application for a candidate user', function () {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $application = Application::factory()->forCandidate($candidate)->create();

        $response = $this->actingAs($candidate)->patch(route('applications.update', $application), [
            'status' => ApplicationStatus::Reviewing->value,
        ]);

        $response->assertForbidden();
    });

    it('does not update an application for a different employer', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $application = Application::factory()->create();

        $response = $this->actingAs($employer)->patch(route('applications.update', $application), [
            'status' => ApplicationStatus::Reviewing->value,
        ]);

        $response->assertForbidden();
    });

    it('updates an application status for the job owner', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->forEmployer($employer)->create();
        $application = Application::factory()->forJob($job)->create([
            'status' => ApplicationStatus::Submitted,
        ]);

        $response = $this->actingAs($employer)->patch(route('applications.update', $application), [
            'status' => ApplicationStatus::Reviewing->value,
        ]);

        $application->refresh();

        expect($application->status)->toBe(ApplicationStatus::Reviewing);

        $response->assertRedirect();

        Notification::assertSentTo(
            $application->candidate,
            ApplicationStatusUpdated::class,
        );
    });

    it('fails to update an application with an invalid status transition', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->forEmployer($employer)->create();
        $application = Application::factory()->forJob($job)->create([
            'status' => ApplicationStatus::Submitted,
        ]);

        $response = $this->actingAs($employer)->patch(route('applications.update', $application), [
            'status' => ApplicationStatus::Hired->value,
        ]);

        $response->assertSessionHasErrors('status');
    });
});

describe('application resume requests', function () {
    beforeEach(function () {
        fakeStorage('local');
    });

    it('redirects unauthenticated users to login', function () {
        $application = Application::factory()->create();

        $response = $this->get(route('applications.resume', $application));

        $response->assertRedirect(route('login'));
    });

    it('does not download a resume for a candidate user', function () {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $application = Application::factory()->forCandidate($candidate)->create([
            'resume' => 'resumes/test.pdf',
        ]);
        Storage::disk('local')->put('resumes/test.pdf', 'resume content');

        $response = $this->actingAs($candidate)->get(route('applications.resume', $application));

        $response->assertForbidden();
    });

    it('downloads a resume for the job owner', function () {
        $employer = User::factory()->create(['role' => 'employer']);
        $candidate = User::factory()->create(['role' => 'candidate', 'name' => 'Jane Candidate']);
        $job = JobListing::factory()->forEmployer($employer)->create();
        $application = Application::factory()->forJob($job)->forCandidate($candidate)->create([
            'resume' => 'resumes/test.pdf',
        ]);
        Storage::disk('local')->put('resumes/test.pdf', 'resume content');

        $response = $this->actingAs($employer)->get(route('applications.resume', $application));

        $response->assertDownload('Jane Candidate_resume.pdf');
    });
});
