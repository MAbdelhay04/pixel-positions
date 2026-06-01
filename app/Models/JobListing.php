<?php

namespace App\Models;

use App\Enums\JobLocation;
use App\Enums\JobStatus;
use App\Enums\JobType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'url', 'salary_range', 'category_id', 'description', 'location', 'type', 'status'])]
class JobListing extends Model
{
    use HasUuids, HasFactory;
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'location'  => JobLocation::class,
            'type'  => JobType::class,
            'status'  => JobStatus::class,
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    public function hasApplied(User $user): bool
    {
        if ($this->relationLoaded('applications')) {
            return $this->applications->where('candidate_id', $user->id)->isNotEmpty();
        }
        return $this->applications()->where('candidate_id', $user->id)->exists();
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'job_tag', 'job_id', 'tag_id');
    }
    public function syncTags(array $names): void
    {
        $tags = collect($names)
            ->map(fn($tag) => strtolower(trim($tag)))
            ->filter()
            ->unique();

        $existing = Tag::whereIn('name', $tags)->get()->keyBy('name');

        $ids = $tags->map(function ($name) use ($existing) {
            return $existing[$name]->id ?? Tag::create(['name' => $name])->id;
        });

        $this->tags()->sync($ids);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_skill', 'job_id', 'skill_id');
    }
    public function syncSkills(array $names): void
    {
        $skills = collect($names)
            ->map(fn($skill) => strtolower(trim($skill)))
            ->filter()
            ->unique();

        $existing = Skill::whereIn('name', $skills)->get()->keyBy('name');

        $ids = $skills->map(function ($name) use ($existing) {
            return $existing[$name]->id ?? Skill::create(['name' => $name])->id;
        });

        $this->skills()->sync($ids);
    }
}
