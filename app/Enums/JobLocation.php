<?php

namespace App\Enums;

enum JobLocation: string
{
    case OnSite = 'on-site';
    case Remote = 'remote';
    case Hybrid  = 'hybrid';

    public function label(): string
    {
        return match($this) {
            self::OnSite => 'On-site',
            self::Remote => 'Remote',
            self::Hybrid  => 'Hybrid',
        };
    }

    /**
     * Tailwind classes for a pill/badge.
     * All classes are spelled out fully so Tailwind's content scanner picks them up.
     */
    public function color(): string
    {
        return match($this) {
            self::OnSite => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-500/30',
            self::Remote => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/20 dark:text-emerald-400 dark:ring-emerald-500/30',
            self::Hybrid  => 'bg-violet-50 text-violet-700 ring-violet-600/20 dark:bg-violet-900/20 dark:text-violet-400 dark:ring-violet-500/30',
        };
    }
}
