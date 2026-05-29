<?php

namespace App\Models;

use App\Casts\Lowercase;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Category extends Model
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
    public function jobs(): HasMany
    {
        return $this->hasMany(JobListing::class);
    }
}
