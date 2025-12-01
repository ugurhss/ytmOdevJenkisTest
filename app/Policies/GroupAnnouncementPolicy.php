<?php

namespace App\Policies;

use App\Models\GroupAnnouncement;
use App\Models\User;

class GroupAnnouncementPolicy
{

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'superadmin']);
    }


    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'superadmin']);
    }


    public function view(User $user, GroupAnnouncement $announcement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $announcement->group->user_id === $user->id;
        }

        return false;
    }


    public function update(User $user, GroupAnnouncement $announcement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $announcement->group->user_id === $user->id;
        }

        return false;
    }


    public function delete(User $user, GroupAnnouncement $announcement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            return $announcement->group->user_id === $user->id;
        }

        return false;
    }
}
