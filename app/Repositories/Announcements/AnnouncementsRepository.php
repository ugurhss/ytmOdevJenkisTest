<?php

namespace App\Repositories\Announcements;

use App\Models\Group;
use App\Models\GroupAnnouncement;
use Illuminate\Pagination\LengthAwarePaginator;

class AnnouncementsRepository
{
    /**
     * 📄 Duyuruları filtreleyip sayfalı döndürür
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = GroupAnnouncement::with(['group.user', 'user'])
            ->orderByDesc('created_at');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['group_id'])) {
            $query->where('group_id', $filters['group_id']);
        }

        if (!empty($filters['group_ids'])) {
            $query->whereIn('group_id', $filters['group_ids']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 👥 Kullanıcıya göre grupları getir
     */
    public function getGroupsForUser($user)
    {
        if ($user->hasRole('superadmin')) {
            $groups = Group::with('user')->orderBy('groups_name')->get();
            $groupIds = null;
        } else {
            $groups = Group::where('user_id', $user->id)
                ->with('user')
                ->orderBy('groups_name')
                ->get();
            $groupIds = $groups->pluck('id')->toArray();
        }

        return [
            'groups'   => $groups,
            'groupIds' => $groupIds,
        ];
    }

    /**
     * 🆕 Yeni duyuru oluştur
     */
    public function create(array $data): GroupAnnouncement
    {
        return GroupAnnouncement::create($data);
    }

    /**
     * 🔍 ID’ye göre duyuru getir
     */
    public function findById(int $id): ?GroupAnnouncement
    {
        return GroupAnnouncement::with(['group.user', 'user'])->find($id);
    }

    /**
     * ✏️ Duyuru güncelle
     */
    public function update(int $id, array $data): ?GroupAnnouncement
    {
        $announcement = GroupAnnouncement::findOrFail($id);
        $announcement->update($data);
        return $announcement->fresh(['group.user', 'user']);
    }

    /**
     * 🗑️ Duyuru sil
     */
    public function delete(int $id): bool
    {
        $announcement = GroupAnnouncement::findOrFail($id);
        return $announcement->delete();
    }
}
