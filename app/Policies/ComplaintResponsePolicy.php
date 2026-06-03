<?php

namespace App\Policies;

use App\Models\ComplaintResponse;
use App\Models\User;

class ComplaintResponsePolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin()
            || $user->isSuperAdmin();
    }

    public function update(
        User $user,
        ComplaintResponse $response
    ): bool {

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin()
            && $response->created_by === $user->id
            && $response->approval_status === 'pending';
    }

    public function approve(
        User $user,
        ComplaintResponse $response
    ): bool {

        return $user->isSupervisor()
            || $user->isSuperAdmin();
    }

    public function reject(
        User $user,
        ComplaintResponse $response
    ): bool {

        return $user->isSupervisor()
            || $user->isSuperAdmin();
    }
}