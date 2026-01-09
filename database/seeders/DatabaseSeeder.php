<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CitySeeder::class,
            UniversitySeeder::class,
            FacultySeeder::class,
            DepartmentSeeder::class,
            ClassModelSeeder::class,
            GroupSeeder::class,
            GroupAnnouncementSeeder::class,
            GroupAnnouncementAttachmentSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
