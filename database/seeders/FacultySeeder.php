<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\University;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        if (Faculty::count() > 0) {
            return;
        }

        $universities = University::all();
        if ($universities->isEmpty()) {
            return;
        }

        foreach ($universities as $university) {
            Faculty::factory()
                ->count(2)
                ->create(['university_id' => $university->id]);
        }
    }
}
