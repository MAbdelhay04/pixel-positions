<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => JobListing::factory(),
            'candidate_id' => User::factory()->state(['role' => 'candidate']),
            'resume' => 'resumes/' . fake()->uuid() . '.pdf',
            'cover_letter' => fake()->optional()->paragraph(),
            'status' => ApplicationStatus::Submitted,
        ];
    }

    public function forJob(JobListing $job): static
    {
        return $this->state(fn() => [
            'job_id' => $job->id,
        ]);
    }

    public function forCandidate(User $candidate): static
    {
        return $this->state(fn() => [
            'candidate_id' => $candidate->id,
        ]);
    }
}
