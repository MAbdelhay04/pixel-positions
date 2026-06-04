<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum JobStatus: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';

    public function isOpen()
    {
        return $this === self::Open;
    }

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Open => 'Open',
            self::Closed => 'Closed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'bg-gray-100 text-gray-600 ring-gray-500/20 dark:bg-gray-700/40 dark:text-gray-400 dark:ring-gray-500/30',
            self::Open => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-500/30',
            self::Closed => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-500/30',
        };
    }

    public static function updatable(): Collection
    {
        return collect([
            self::Open,
            self::Closed,
        ]);
    }

    public function canTransitionTo(self $next): bool
    {
        if ($this === $next) {
            return false;
        }

        return match ($this) {
            self::Draft => in_array($next, [self::Open, self::Closed], true),
            self::Open => $next === self::Closed,
            self::Closed => $next === self::Open,
        };
    }
}
