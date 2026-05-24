<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Submitted = 'submitted';
    case Reviewing = 'reviewing';
    case Interview = 'interview';
    case Hired = 'hired';
    case Rejected = 'rejected';
}
