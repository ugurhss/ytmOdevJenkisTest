<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupAnnouncement;
use Illuminate\Database\Seeder;

class GroupAnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        if (GroupAnnouncement::count() > 0) {
            return;
        }

        $groups = Group::all();
        if ($groups->isEmpty()) {
            return;
        }

        foreach ($groups as $group) {
            foreach (range(1, 2) as $idx) {
                GroupAnnouncement::create([
                    'group_id' => $group->id,
                    'user_id' => $group->user_id,
                    'title' => fake()->sentence(4),
                    'content' => fake()->paragraph(3),
                ]);
            }
        }
    }
}
