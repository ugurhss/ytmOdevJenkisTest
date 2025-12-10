<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use App\Services\Activity\ActivityLogService;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    public function create(User $user): Response
    {
        return $user->hasAnyRole(['admin', 'superadmin'])
            ? Response::allow()
            : Response::deny('Grup oluşturma yetkiniz yok.');
    }

    public function view(User $user, Group $group): Response
    {

        if ($user->hasRole('superadmin')) {
            return Response::allow();
        }

        if ($user->hasRole('admin') && $group->user_id === $user->id) {
            return Response::allow();
        }

        $isMember = $group->students()->where('user_id', $user->id)->exists();
        if ($group->user_id === $user->id || $isMember) {
            return Response::allow();
        }

        app(ActivityLogService::class)
            ->logUnauthorizedGroupAccess($user, $group, 'view');

        return Response::deny('Bu grubu görüntüleme yetkiniz yok.');
    }

    public function update(User $user, Group $group): Response
    {
        if ($user->hasRole('superadmin')) {
            return Response::allow();
        }

        if ($user->hasRole('admin') && $group->user_id === $user->id) {
            return Response::allow();
        }

        app(ActivityLogService::class)
            ->logUnauthorizedGroupAccess($user, $group, 'update');

        return Response::deny('Bu grubu güncelleme yetkiniz yok.');
    }

    public function delete(User $user, Group $group): Response
    {
        if ($user->hasRole('superadmin')) {
            return Response::allow();
        }

        if ($user->hasRole('admin') && $group->user_id === $user->id) {
            return Response::allow();
        }

        app(ActivityLogService::class)
            ->logUnauthorizedGroupAccess($user, $group, 'delete');

        return Response::deny('Bu grubu silme yetkiniz yok.');
    }


    public function viewAny(User $user): bool
{
    return $user->hasAnyRole([ 'superadmin']);
}
}
