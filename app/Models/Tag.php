<?php

namespace App\Models;

use App\Casts\Lowercase;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

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

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(JobListing::class, 'job_tag', 'tag_id', 'job_id');
    }
}
