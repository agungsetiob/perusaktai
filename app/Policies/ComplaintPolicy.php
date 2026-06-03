<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Complaint $complaint): bool
    {
        return true;
    }

    public function update(User $user, Complaint $complaint): bool
    {
        return $user->isAdmin()
            || $user->isSupervisor()
            || $user->isSuperAdmin();
    }

    public function reject(User $user): bool
    {
        return $user->isSupervisor()
            || $user->isSuperAdmin();
    }

    public function forceDelete(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function solve(
        User $user,
        Complaint $complaint
    ): bool {

        return in_array(
            $user->role,
            [
                'admin',
                'super_admin',
            ]
        );
    }
}