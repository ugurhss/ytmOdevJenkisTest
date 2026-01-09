<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Department;
use Illuminate\Database\Seeder;

class ClassModelSeeder extends Seeder
{
    public function run(): void
    {
        if (ClassModel::count() > 0) {
            return;
        }

        $departments = Department::all();
        if ($departments->isEmpty()) {
            return;
        }

        foreach ($departments as $department) {
            foreach (range(1, 4) as $classNo) {
                ClassModel::create([
                    'department_id' => $department->id,
                    'name' => (string) $classNo,
                ]);
            }
        }
    }
}
