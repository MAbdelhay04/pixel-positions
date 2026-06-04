<?php

namespace App\Policies;

use App\Models\User;

class EmployerPolicy
{
    public function view(User $user): bool
    {
        return $user->isEmployer();
    }

    public function update(User $user): bool
    {
        return $user->isEmployer();
    }
}
