<?php

namespace App\Enums;

enum JobLocation: string
{
    case OnSite = 'on-site';
    case Remote = 'remote';
    case Hybrid = 'hybrid';
}
