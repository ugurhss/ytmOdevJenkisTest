<?php

namespace Database\Seeders;

use App\Models\GroupAnnouncement;
use App\Models\GroupAnnouncementAttachment;
use Illuminate\Database\Seeder;

class GroupAnnouncementAttachmentSeeder extends Seeder
{
    public function run(): void
    {
        if (GroupAnnouncementAttachment::count() > 0) {
            return;
        }

        $announcements = GroupAnnouncement::all();
        if ($announcements->isEmpty()) {
            return;
        }

        foreach ($announcements as $announcement) {
            if (random_int(0, 1) === 0) {
                continue;
            }

            GroupAnnouncementAttachment::create([
                'group_announcement_id' => $announcement->id,
                'original_name' => fake()->word() . '.pdf',
                'path' => 'attachments/' . fake()->uuid() . '.pdf',
                'mime_type' => 'application/pdf',
                'type' => 'document',
                'size' => random_int(20_000, 2_000_000),
            ]);
        }
    }
}
