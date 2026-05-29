<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Submitted = 'submitted';
    case Reviewing = 'reviewing';
    case Interview = 'interview';
    case Hired     = 'hired';
    case Rejected  = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::Submitted => 'Submitted',
            self::Reviewing => 'Reviewing',
            self::Interview => 'Interview',
            self::Hired     => 'Hired',
            self::Rejected  => 'Rejected',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Submitted => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-500/30',
            self::Reviewing => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/20 dark:text-amber-400 dark:ring-amber-500/30',
            self::Interview => 'bg-violet-50 text-violet-700 ring-violet-600/20 dark:bg-violet-900/20 dark:text-violet-400 dark:ring-violet-500/30',
            self::Hired     => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-500/30',
            self::Rejected  => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-500/30',
        };
    }
}
