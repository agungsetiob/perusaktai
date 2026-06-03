<?php

namespace App\Policies;

use App\Models\ComplaintCategory;
use App\Models\User;

class ComplaintCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'super_admin' || $user->role === 'admin' || $user->role === 'supervisor';
    }

    public function view(
        User $user,
        ComplaintCategory $category
    ): bool {
        return $user->role === 'super_admin' || $user->role === 'admin' || $user->role === 'supervisor';
    }

    public function create(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function update(
        User $user,
        ComplaintCategory $category
    ): bool {
        return $user->role === 'super_admin';
    }

    public function delete(
        User $user,
        ComplaintCategory $category
    ): bool {
        return $user->role === 'super_admin';
    }
}