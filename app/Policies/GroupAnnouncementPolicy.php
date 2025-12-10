<?php

namespace App\Policies;

use App\Models\GroupAnnouncement;
use App\Models\User;
use App\Services\Activity\ActivityLogService;
use Illuminate\Auth\Access\Response;

class GroupAnnouncementPolicy
{
    public function create(User $user): Response
    {
        return $user->hasAnyRole(['admin', 'superadmin'])
            ? Response::allow()
            : Response::deny('Duyuru oluşturma yetkiniz yok.');
    }

    public function viewAny(User $user): Response
    {
        return $user->hasAnyRole(['admin', 'superadmin'])
            ? Response::allow()
            : Response::deny('Duyuru listesini görüntüleme yetkiniz yok.');
    }

    public function view(User $user, GroupAnnouncement $announcement): Response
    {
        if ($user->hasRole('superadmin')) {
            return Response::allow();
        }

        if ($user->hasRole('admin') &&
            $announcement->group &&
            $announcement->group->user_id === $user->id
        ) {
            return Response::allow();
        }

        app(ActivityLogService::class)
            ->logUnauthorizedAnnouncementAccess($user, $announcement, 'view');

        return Response::deny('Bu duyuruyu görüntüleme yetkiniz yok.');
    }

    public function update(User $user, GroupAnnouncement $announcement): Response
    {
        if ($user->hasRole('superadmin')) {
            return Response::allow();
        }

        if ($user->hasRole('admin') &&
            $announcement->group &&
            $announcement->group->user_id === $user->id
        ) {
            return Response::allow();
        }

        app(ActivityLogService::class)
            ->logUnauthorizedAnnouncementAccess($user, $announcement, 'update');

        return Response::deny('Bu duyuruyu güncelleme yetkiniz yok.');
    }

    public function delete(User $user, GroupAnnouncement $announcement): Response
    {
        if ($user->hasRole('superadmin')) {
            return Response::allow();
        }

        if ($user->hasRole('admin') &&
            $announcement->group &&
            $announcement->group->user_id === $user->id
        ) {
            return Response::allow();
        }

        app(ActivityLogService::class)
            ->logUnauthorizedAnnouncementAccess($user, $announcement, 'delete');

        return Response::deny('Bu duyuruyu silme yetkiniz yok.');
    }
}
