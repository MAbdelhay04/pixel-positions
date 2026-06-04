<?php

namespace App\Models;

use App\Casts\Lowercase;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
class Skill extends Model
{
    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => Lowercase::class,
        ];
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(JobListing::class, 'job_skill');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skill');
    }
}
