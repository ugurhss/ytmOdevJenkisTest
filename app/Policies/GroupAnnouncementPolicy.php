<?php

namespace App\Policies;

use App\Models\GroupAnnouncement;
use App\Models\User;

class GroupAnnouncementPolicy
{
    /**
     * Duyuru oluşturabilir mi?
     */
    public function create(User $user): bool
    {
        // Sadece admin veya superadmin oluşturabilir
        return $user->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Duyuruları listeleyebilir mi?
     */
    public function viewAny(User $user): bool
    {
        // Admin ve superadmin listeleyebilir
        return $user->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Duyuruyu görüntüleyebilir mi?
     */
    public function view(User $user, GroupAnnouncement $announcement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            // Admin sadece kendi grubundaki duyuruları görebilir
            return $announcement->group->user_id === $user->id;
        }

        return false;
    }

    /**
     * Duyuruyu güncelleyebilir mi?
     */
    public function update(User $user, GroupAnnouncement $announcement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            // Admin sadece kendi grubundaki duyuruları güncelleyebilir
            return $announcement->group->user_id === $user->id;
        }

        return false;
    }

    /**
     * Duyuruyu silebilir mi?
     */
    public function delete(User $user, GroupAnnouncement $announcement): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            // Admin sadece kendi grubundaki duyuruları silebilir
            return $announcement->group->user_id === $user->id;
        }

        return false;
    }
}
