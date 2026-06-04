<?php

namespace Database\Factories;

use App\Enums\JobLocation;
use App\Enums\JobStatus;
use App\Enums\JobType;
use App\Models\Category;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobListing>
 */
class JobListingFactory extends Factory
{
    protected $model = JobListing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'url' => fake()->optional()->url(),

            'salary_range' => fake()->randomElement([
                '$1000 - $2000',
                '$2000 - $4000',
                '$4000 - $8000',
                '$8000 - $12000',
            ]),

            'category_id' => Category::inRandomOrder()->first()?->id,

            'description' => fake()->text(300),

            'location' => fake()->randomElement(
                array_map(fn ($case) => $case->value, JobLocation::cases())
            ),

            'type' => fake()->randomElement(
                array_map(fn ($case) => $case->value, JobType::cases())
            ),

            'status' => fake()->randomElement(
                array_map(fn ($case) => $case->value, JobStatus::cases())
            ),

            // default employer (can be overridden in seeder)
            'employer_id' => User::factory(),
        ];
    }

    /**
     * Override employer_id with current authenticated user or specific user
     */
    public function forEmployer(User $user): static
    {
        return $this->state(fn () => [
            'employer_id' => $user->id,
        ]);
    }
}
