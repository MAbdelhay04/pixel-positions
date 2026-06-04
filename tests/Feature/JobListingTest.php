<?php

use App\Enums\JobLocation;
use App\Enums\JobStatus;
use App\Enums\JobType;
use App\Models\Category;
use App\Models\JobListing;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Str;

describe('job listing index requests', function () {
    it('redirects home to the job listing index', function () {
        $response = $this->get('/');

        $response->assertRedirect(route('jobs.index'));
    });

    it('shows the job listing index view', function () {
        $response = $this->get(route('jobs.index'));

        $response->assertViewIs('jobs.index');
    });

    it('shows the job listing index view filtered by tag', function () {
        $tag = Tag::factory()->create();
        $response = $this->get(route('jobs.index_tag', $tag));

        $response->assertViewIs('jobs.index');
    });

    it('shows the job listing results partial for ajax requests', function () {
        $response = $this->get(route('jobs.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertViewIs('jobs._results');
    });

    it('shows the job listing results partial for ajax requests filtered by tag', function () {
        $tag = Tag::factory()->create();
        $response = $this->get(route('jobs.index_tag', $tag), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertViewIs('jobs._results');
    });
});

describe('job listing create form requests', function () {
    it('redirects unauthenticated users to login', function () {
        $response = $this->get(route('jobs.create'));

        $response->assertRedirect(route('login'));
    });

    it('does not show the create form for a candidate user', function () {
        $user = User::factory()->create(['role' => 'candidate']);

        $response = $this->actingAs($user)->get(route('jobs.create'));

        $response->assertForbidden();
    });

    it('shows the create form for an employer user', function () {
        $user = User::factory()->create(['role' => 'employer']);

        $response = $this->actingAs($user)->get(route('jobs.create'));

        $response->assertViewIs('jobs.create');
    });
});

describe('job listing store requests', function () {
    it('redirects unauthenticated users to login', function () {
        $response = $this->post(route('jobs.store'));

        $response->assertRedirect(route('login'));
    });

    it('does not store a job for a candidate user', function () {
        $user = User::factory()->create(['role' => 'candidate']);

        $response = $this->actingAs($user)->post(route('jobs.store'));

        $response->assertForbidden();
    });

    it('stores a job for an employer user', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('jobs.store'), [
            'title' => 'New Job Posted',
            'url' => 'https://pixel-position.test',
            'salary_range' => '$50K - $60K',
            'category_id' => $category->id,
            'description' => Str::random('200'),
            'location' => JobLocation::Hybrid->value,
            'type'  => JobType::Contract->value,
            'status' => JobStatus::Draft->value,
            'skills' => ['php', 'laravel', 'testing'],
            'tags' => ['new', 'trending', 'software'],
        ]);

        $this->assertDatabaseHas('job_listings', ['title' => 'New Job Posted']);

        $job = JobListing::where('title', 'New Job Posted')->first();
        $response->assertRedirect(route('jobs.show', $job));
    });

    it('fails to store a job with invalid data', function () {
        $user = User::factory()->create(['role' => 'employer']);

        $response = $this->actingAs($user)->post(route('jobs.store'), [
            'title' => '',
            'url' => 'not-a-url',
            'salary_range' => '',
            'category_id' => 999,
            'location' => 'invalid',
            'type' => 'invalid',
            'status' => 'invalid',
            'skills' => ['a'],
            'tags' => ['b'],
        ]);

        $response->assertSessionHasErrors([
            'title',
            'salary_range',
            'category_id',
            'location',
            'type',
            'status',
            'skills.*',
            'tags.*',
        ]);
    });
});

describe('job listing show requests', function () {
    it('shows the job listing details view', function () {
        $job = JobListing::factory()->create();

        $response = $this->get(route('jobs.show', $job));

        $response->assertViewIs('jobs.show');
    });
});

describe('job listing edit form requests', function () {
    it('redirects unauthenticated users to login', function () {
        $job = JobListing::factory()->create();

        $response = $this->get(route('jobs.edit', $job));

        $response->assertRedirect(route('login'));
    });

    it('does not show the edit form for a candidate user', function () {
        $user = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($user)->get(route('jobs.edit', $job));

        $response->assertForbidden();
    });

    it('does not show the edit form for a different employer', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($user)->get(route('jobs.edit', $job));

        $response->assertForbidden();
    });

    it('shows the edit form for the job owner', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create(['employer_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('jobs.edit', $job));

        $response->assertViewIs('jobs.edit');
    });
});

describe('job listing update requests', function () {
    it('redirects unauthenticated users to login', function () {
        $job = JobListing::factory()->create();

        $response = $this->patch(route('jobs.update', $job));

        $response->assertRedirect(route('login'));
    });

    it('does not update a job for a candidate user', function () {
        $user = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($user)->patch(route('jobs.update', $job));

        $response->assertForbidden();
    });

    it('does not update a job for a different employer', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($user)->patch(route('jobs.update', $job));

        $response->assertForbidden();
    });

    it('updates a job for the job owner', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create(['employer_id' => $user->id]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->patch(route('jobs.update', $job), [
            'title' => 'Job Title Updated',
            'url' => 'https://pixel-position.test',
            'salary_range' => '$57K - $67K',
            'category_id' => $category->id,
            'description' => Str::random('200'),
            'location' => JobLocation::Hybrid->value,
            'type'  => JobType::Contract->value,
            'status' => JobStatus::Draft->value,
            'skills' => ['php', 'laravel', 'testing'],
            'tags' => ['new', 'trending', 'software'],
        ]);

        $job->refresh();

        $this->assertDatabaseHas('job_listings', ['uuid' => $job->uuid, 'title' => 'Job Title Updated']);

        $response->assertRedirect(route('jobs.show', $job));
    });

    it('fails to update a job with invalid data', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create(['employer_id' => $user->id]);

        $response = $this->actingAs($user)->patch(route('jobs.update', $job), [
            'title' => '',
            'url' => 'not-a-url',
            'salary_range' => '',
            'category_id' => 999,
            'location' => 'invalid',
            'type' => 'invalid',
            'status' => 'invalid',
            'skills' => ['a'],
            'tags' => ['b'],
        ]);

        $response->assertSessionHasErrors([
            'title',
            'salary_range',
            'category_id',
            'location',
            'type',
            'status',
            'skills.*',
            'tags.*',
        ]);
    });
});

describe('job listing status update requests', function () {
    it('redirects unauthenticated users to login', function () {
        $job = JobListing::factory()->create();

        $response = $this->patch(route('jobs.update_status', $job));

        $response->assertRedirect(route('login'));
    });

    it('does not update a job status for a candidate user', function () {
        $user = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($user)->patch(route('jobs.update_status', $job));

        $response->assertForbidden();
    });

    it('does not update a job status for a different employer', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($user)->patch(route('jobs.update_status', $job));

        $response->assertForbidden();
    });

    it('updates a job status for the job owner', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create([
            'employer_id' => $user->id,
            'status' => JobStatus::Draft->value
        ]);

        $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
            'status' => JobStatus::Open->value,
        ]);

        $job->refresh();

        $this->assertDatabaseHas('job_listings', ['title' => $job->title, 'status' => $job->status]);

        $response->assertRedirectBack();
    });

    it('fails to update a job status with invalid data', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Draft->value]);

        $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
            'status' => 'invalid',
        ]);

        $response->assertSessionHasErrors('status');
    });

    describe('job listing status transitions', function () {
        it('fails to transition to the same status', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => $job->status->value,
            ]);

            $response->assertSessionHasErrors('status');
        });

        it('fails to transition from draft to draft', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Draft->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Draft->value,
            ]);

            $response->assertSessionHasErrors('status');
        });

        it('transitions from draft to open', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Draft->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Open->value,
            ]);

            $response->assertRedirectBack();
        });

        it('transitions from draft to closed', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Draft->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Closed->value,
            ]);

            $response->assertRedirectBack();
        });

        it('fails to transition from open to draft', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Open->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Draft->value,
            ]);

            $response->assertSessionHasErrors('status');
        });

        it('fails to transition from open to open', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Open->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Open->value,
            ]);

            $response->assertSessionHasErrors('status');
        });

        it('transitions from open to closed', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Open->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Closed->value,
            ]);

            $response->assertRedirectBack();
        });

        it('fails to transition from closed to draft', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Closed->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Draft->value,
            ]);

            $response->assertSessionHasErrors('status');
        });

        it('transitions from closed to open', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Closed->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Open->value,
            ]);

            $response->assertRedirectBack();
        });

        it('fails to transition from closed to closed', function () {
            $user = User::factory()->create(['role' => 'employer']);
            $job = JobListing::factory()->create(['employer_id' => $user->id, 'status' => JobStatus::Closed->value]);

            $response = $this->actingAs($user)->patch(route('jobs.update_status', $job), [
                'status' => JobStatus::Closed->value,
            ]);

            $response->assertSessionHasErrors('status');
        });
    });
});

describe('job listing destroy requests', function () {
    it('redirects unauthenticated users to login', function () {
        $job = JobListing::factory()->create();

        $response = $this->delete(route('jobs.destroy', $job));

        $response->assertRedirect(route('login'));
    });

    it('does not delete a job for a candidate user', function () {
        $user = User::factory()->create(['role' => 'candidate']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($user)->delete(route('jobs.destroy', $job));

        $response->assertForbidden();
    });

    it('does not delete a job for a different employer', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create();

        $response = $this->actingAs($user)->delete(route('jobs.destroy', $job));

        $response->assertForbidden();
    });

    it('deletes a job for the job owner', function () {
        $user = User::factory()->create(['role' => 'employer']);
        $job = JobListing::factory()->create(['employer_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('jobs.destroy', $job));

        $this->assertDatabaseMissing('job_listings', ['uuid' => $job->uuid]);

        $response->assertRedirect(route('dashboard'));
    });
});
