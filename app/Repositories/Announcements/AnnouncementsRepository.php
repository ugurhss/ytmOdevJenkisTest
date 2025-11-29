<?php

namespace App\Repositories\Announcements;

use App\Models\GroupAnnouncement;
use Illuminate\Pagination\LengthAwarePaginator;

class AnnouncementsRepository
{
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

    public function create(array $data): GroupAnnouncement
    {
        return GroupAnnouncement::create($data);
    }

    public function findById(int $id): ?GroupAnnouncement
    {
        return GroupAnnouncement::with(['group.user', 'user'])->find($id);
    }

    public function update(int $id, array $data): ?GroupAnnouncement
    {
        $announcement = GroupAnnouncement::findOrFail($id);
        $announcement->update($data);
        return $announcement->fresh(['group.user', 'user']);
    }

    public function delete(int $id): bool
    {
        $announcement = GroupAnnouncement::findOrFail($id);
        return $announcement->delete();
    }
}
