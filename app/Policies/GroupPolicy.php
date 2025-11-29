<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'superadmin']);
    }

    public function view(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $group->user_id === $user->id;
        }

        $isMember = $group->students()->where('user_id', $user->id)->exists();
        return $group->user_id === $user->id || $isMember;
    }


    public function update(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $group->user_id === $user->id;
        }

        return false;
    }


    public function delete(User $user, Group $group): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $group->user_id === $user->id;
        }

        return false;
    }
}
