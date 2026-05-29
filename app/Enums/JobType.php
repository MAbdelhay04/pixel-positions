<?php

namespace App\Enums;

enum JobType: string
{
    case FullTime   = 'full-time';
    case PartTime   = 'part-time';
    case Contract   = 'contract';
    case Freelance  = 'freelance';
    case Internship = 'internship';

    public function label(): string
    {
        return match($this) {
            self::FullTime   => 'Full-time',
            self::PartTime   => 'Part-time',
            self::Contract   => 'Contract',
            self::Freelance  => 'Freelance',
            self::Internship => 'Internship',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::FullTime   => 'bg-sky-50 text-sky-700 ring-sky-600/20 dark:bg-sky-900/20 dark:text-sky-400 dark:ring-sky-500/30',
            self::PartTime   => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/20 dark:text-amber-400 dark:ring-amber-500/30',
            self::Contract   => 'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-900/20 dark:text-orange-400 dark:ring-orange-500/30',
            self::Freelance  => 'bg-pink-50 text-pink-700 ring-pink-600/20 dark:bg-pink-900/20 dark:text-pink-400 dark:ring-pink-500/30',
            self::Internship => 'bg-teal-50 text-teal-700 ring-teal-600/20 dark:bg-teal-900/20 dark:text-teal-400 dark:ring-teal-500/30',
        };
    }
}
