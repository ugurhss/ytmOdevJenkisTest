<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\GroupAnnouncement;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        if (ActivityLog::count() > 0) {
            return;
        }

        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $groups = Group::all();
        foreach ($groups as $group) {
            $actor = $users->random();

            ActivityLog::create([
                'event' => 'group_created',
                'description' => "Grup olusturuldu: {$group->groups_name}",
                'actor_id' => $actor->id,
                'actor_name' => $actor->name,
                'subject_type' => Group::class,
                'subject_id' => $group->id,
                'meta' => [
                    'city_id' => $group->city_id,
                    'university_id' => $group->university_id,
                ],
            ]);
        }

        $announcements = GroupAnnouncement::all();
        foreach ($announcements as $announcement) {
            $actor = $users->random();

            ActivityLog::create([
                'event' => 'announcement_created',
                'description' => 'Duyuru olusturuldu: ' . $announcement->title,
                'actor_id' => $actor->id,
                'actor_name' => $actor->name,
                'subject_type' => GroupAnnouncement::class,
                'subject_id' => $announcement->id,
                'meta' => [
                    'group_id' => $announcement->group_id,
                ],
            ]);
        }
    }
}
