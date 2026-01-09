<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        if (Department::count() > 0) {
            return;
        }

        $faculties = Faculty::all();
        if ($faculties->isEmpty()) {
            return;
        }

        foreach ($faculties as $faculty) {
            Department::factory()
                ->count(3)
                ->create(['faculty_id' => $faculty->id]);
        }
    }
}
