<?php

namespace App\Services\Announcements;

use App\Repositories\Announcements\AnnouncementsRepository;
use App\Services\Group\GroupService;
use App\Models\Group;
use Illuminate\Http\Request;

class AnnouncementService
{
    protected AnnouncementsRepository $announcementRepo;
    protected GroupService $groupService;

    public function __construct(
        AnnouncementsRepository $announcementRepo,
        GroupService $groupService
    ) {
        $this->announcementRepo = $announcementRepo;
        $this->groupService = $groupService;
    }


    public function getIndexData($user, Request $request)
    {

        if ($user->hasRole('superadmin')) {
            $groups = $this->groupService->getAll();
            $groupIds = null;
        } else {
            $groups = $this->groupService->getGroupsByUser($user->id);
            $groupIds = $groups->pluck('id')->toArray();
        }

        $filters = [
            'search'   => $request->input('search'),
            'group_id' => $request->input('group_id'),
        ];

        if ($groupIds !== null) {
            $filters['group_ids'] = $groupIds;
        }


        $announcements = $this->announcementRepo->getPaginated($filters, 15);

        return [
            'announcements' => $announcements,
            'groups'        => $groups,
            'filters'       => $request->only(['group_id', 'search']),
        ];
    }


    public function createWithRelations(array $data, $user)
    {
        $group = Group::findOrFail($data['group_id']);


        $user->can('view', $group) ?: abort(403, 'Bu gruba erişim yetkiniz yok.');

        $data['user_id'] = $user->id;

        return $this->announcementRepo->create($data);
    }


    public function findById(int $id)
    {
        $announcement = $this->announcementRepo->findById($id);
        return $announcement ?? abort(404, 'Duyuru bulunamadı.');
    }


    public function updateWithRelations(int $id, array $data)
    {
        return $this->announcementRepo->update($id, $data);
    }


    public function delete(int $id)
    {
        return $this->announcementRepo->delete($id);
    }
}
