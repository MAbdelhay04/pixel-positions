<?php

namespace App\Models;

use App\Enums\JobLocation;
use App\Enums\JobStatus;
use App\Enums\JobType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasUuids;
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
}
