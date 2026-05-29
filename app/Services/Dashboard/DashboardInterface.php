<?php

namespace App\Services\Dashboard;

use App\Models\User;

interface DashboardInterface
{
    public function render(User $user);
}
