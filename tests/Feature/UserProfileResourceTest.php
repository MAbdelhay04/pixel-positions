<?php

use App\Enums\ApplicationStatus;
use App\Enums\JobStatus;
use App\Enums\UserRole;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('candidate profile resource', function () {
    test('candidate can view and edit profile pages', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $this->actingAs($user)
            ->get(route('users.candidate.show'))
            ->assertOk()
            ->assertSee('Candidate Profile');

        $this->actingAs($user)
            ->get(route('users.candidate.edit'))
            ->assertOk()
            ->assertSee('Edit Candidate Profile');
    });

    test('candidate can update profile data json', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $response = $this
            ->actingAs($user)
            ->patch(route('users.candidate.update'), [
                'headline' => 'Laravel Developer',
                'bio' => 'I build job board products.',
                'work_experience' => 'Acme Inc, Backend Developer',
                'education' => 'BSc Computer Science',
                'location' => 'Cairo',
                'portfolio_url' => 'https://example.com',
                'availability' => 'Open to work',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('users.candidate.show'));

        expect($user->refresh()->profile_data)->toMatchArray([
            'headline' => 'Laravel Developer',
            'bio' => 'I build job board products.',
            'work_experience' => 'Acme Inc, Backend Developer',
            'education' => 'BSc Computer Science',
            'location' => 'Cairo',
            'portfolio_url' => 'https://example.com',
            'availability' => 'Open to work',
        ]);
    });

    test('candidate profile rejects invalid urls', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $response = $this
            ->actingAs($user)
            ->from(route('users.candidate.edit'))
            ->patch(route('users.candidate.update'), [
                'portfolio_url' => 'not-a-url',
            ]);

        $response
            ->assertSessionHasErrors('portfolio_url')
            ->assertRedirect(route('users.candidate.edit'));
    });

    test('employer cannot manage candidate profile', function () {
        $user = User::factory()->create(['role' => UserRole::Employer]);

        $this->actingAs($user)
            ->get(route('users.candidate.show'))
            ->assertForbidden();

        $this->actingAs($user)
            ->patch(route('users.candidate.update'), ['bio' => 'Nope'])
            ->assertForbidden();
    });

    test('employer can view candidate profile after candidate applied to their job', function () {
        $employer = User::factory()->create(['role' => UserRole::Employer]);
        $candidate = User::factory()->create([
            'role' => UserRole::Candidate,
            'profile_data' => [
                'headline' => 'Product-minded Laravel Developer',
                'bio' => 'I build hiring workflows.',
            ],
        ]);
        $job = JobListing::factory()->create([
            'employer_id' => $employer->id,
            'status' => JobStatus::Open,
        ]);

        $job->applications()->create([
            'candidate_id' => $candidate->id,
            'resume' => 'resumes/candidate.pdf',
            'status' => ApplicationStatus::Submitted,
        ]);

        $this->actingAs($employer)
            ->get(route('candidates.show', $candidate))
            ->assertOk()
            ->assertSee($candidate->name)
            ->assertSee('Product-minded Laravel Developer')
            ->assertDontSee('Edit Profile');
    });

    test('employer cannot view candidate profile without an application to their job', function () {
        $employer = User::factory()->create(['role' => UserRole::Employer]);
        $otherEmployer = User::factory()->create(['role' => UserRole::Employer]);
        $candidate = User::factory()->create(['role' => UserRole::Candidate]);
        $job = JobListing::factory()->create([
            'employer_id' => $otherEmployer->id,
            'status' => JobStatus::Open,
        ]);

        $job->applications()->create([
            'candidate_id' => $candidate->id,
            'resume' => 'resumes/candidate.pdf',
            'status' => ApplicationStatus::Submitted,
        ]);

        $this->actingAs($employer)
            ->get(route('candidates.show', $candidate))
            ->assertForbidden();
    });
});

describe('employer profile resource', function () {
    test('employer can view profile and company settings pages', function () {
        $user = User::factory()->create(['role' => UserRole::Employer]);

        $this->actingAs($user)
            ->get(route('users.employer.show'))
            ->assertOk()
            ->assertSee('Company Profile');

        $this->actingAs($user)
            ->get(route('users.employer.settings'))
            ->assertOk()
            ->assertSee('Company Settings');
    });

    test('employer can update company profile data and logo', function () {
        fakePublicStorage();

        $user = User::factory()->create(['role' => UserRole::Employer]);

        $response = $this
            ->actingAs($user)
            ->patch(route('users.employer.update'), [
                'company_name' => 'Pixel Positions',
                'company_description' => 'A focused hiring platform.',
                'company_location' => 'Remote',
                'company_website' => 'https://company.example',
                'company_size' => '11-50',
                'industry' => 'Software',
                'logo' => UploadedFile::fake()->image('company.png', 300, 300),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('users.employer.show'));

        $user->refresh();

        expect($user->name)->toBe('Pixel Positions')
            ->and($user->profile_data)->toMatchArray([
                'company_description' => 'A focused hiring platform.',
                'company_location' => 'Remote',
                'company_website' => 'https://company.example',
                'company_size' => '11-50',
                'industry' => 'Software',
            ])
            ->and($user->logo)->toStartWith('logos/')
            ->and($user->logo)->toEndWith('.webp');

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($user->logo);
    });

    test('employer profile requires a company name', function () {
        $user = User::factory()->create(['role' => UserRole::Employer]);

        $response = $this
            ->actingAs($user)
            ->from(route('users.employer.edit'))
            ->patch(route('users.employer.update'), [
                'company_name' => '',
            ]);

        $response
            ->assertSessionHasErrors('company_name')
            ->assertRedirect(route('users.employer.edit'));
    });

    test('candidate cannot manage employer profile', function () {
        $user = User::factory()->create(['role' => UserRole::Candidate]);

        $this->actingAs($user)
            ->get(route('users.employer.show'))
            ->assertForbidden();

        $this->actingAs($user)
            ->patch(route('users.employer.update'), ['company_name' => 'Nope'])
            ->assertForbidden();
    });

    test('company profile is publicly visible', function () {
        $employer = User::factory()->create([
            'role' => UserRole::Employer,
            'name' => 'Pixel Positions',
            'profile_data' => [
                'company_description' => 'A focused hiring platform.',
                'industry' => 'Software',
            ],
        ]);

        $this->get(route('companies.show', $employer))
            ->assertOk()
            ->assertSee('Pixel Positions')
            ->assertSee('A focused hiring platform.')
            ->assertSee('Open Jobs')
            ->assertDontSee('Edit Profile');
    });

    test('company job listing page shows open jobs from that employer', function () {
        $employer = User::factory()->create(['role' => UserRole::Employer, 'name' => 'Pixel Positions']);
        $otherEmployer = User::factory()->create(['role' => UserRole::Employer]);

        $openJob = JobListing::factory()->create([
            'employer_id' => $employer->id,
            'title' => 'Senior Laravel Developer',
            'status' => JobStatus::Open,
        ]);
        $closedJob = JobListing::factory()->create([
            'employer_id' => $employer->id,
            'title' => 'Closed Laravel Developer',
            'status' => JobStatus::Closed,
        ]);
        $otherJob = JobListing::factory()->create([
            'employer_id' => $otherEmployer->id,
            'title' => 'Other Company Role',
            'status' => JobStatus::Open,
        ]);

        $this->get(route('companies.jobs', $employer))
            ->assertOk()
            ->assertSee('Open Jobs at Pixel Positions')
            ->assertSee($openJob->title)
            ->assertDontSee($closedJob->title)
            ->assertDontSee($otherJob->title);
    });
});
