<?php

namespace App\Enums;

enum UserRole: string
{
    case Employer = 'employer';
    case Candidate = 'candidate';

    public function label(): string
    {
        return match ($this) {
            self::Employer => 'Employer',
            self::Candidate => 'Candidate',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Employer => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20 dark:bg-indigo-900/20 dark:text-indigo-400 dark:ring-indigo-500/30',
            self::Candidate => 'bg-cyan-50 text-cyan-700 ring-cyan-600/20 dark:bg-cyan-900/20 dark:text-cyan-400 dark:ring-cyan-500/30',
        };
    }
}
