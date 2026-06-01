<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\Lowercase;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'username', 'profile_data', 'logo'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'username' => Lowercase::class,
            'profile_data' => 'array',
            'role'  => UserRole::class
        ];
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function isEmployer()
    {
        return $this->role === UserRole::Employer;
    }
    public function isCandidate()
    {
        return $this->role === UserRole::Candidate;
    }


    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'candidate_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(JobListing::class, 'employer_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skill');
    }
    public function attachSkills(array $names): void
    {
        $ids = collect($names)->map(function ($name) {
            return Skill::firstOrCreate([
                'name' => strtolower(trim($name))
            ])->id;
        });

        $this->tags()->syncWithoutDetaching($ids);
    }
    public function detachSkills(array $names): void
    {
        $names = array_map(fn($name) => strtolower(trim($name)), $names);
        $ids = Skill::whereIn('name', $names)->pluck('id');
        $this->tags()->detach($ids);
    }
}
