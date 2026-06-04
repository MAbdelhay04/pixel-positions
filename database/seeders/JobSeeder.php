<?php

namespace Database\Seeders;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email','jobs@pixel-positions.com')->first();
        JobListing::factory()
            ->count(50)
            ->create(['employer_id' => $user->id]);
    }
}
