<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $target = 10;
        if (Group::count() >= $target) {
            return;
        }

        $users = User::all();
        $classModels = ClassModel::with('department.faculty.university.city')->get();

        if ($users->isEmpty() || $classModels->isEmpty()) {
            return;
        }

        while (Group::count() < $target) {
            $classModel = $classModels->random();
            $department = $classModel->department;
            $faculty = $department?->faculty;
            $university = $faculty?->university;
            $city = $university?->city;

            if (!$department || !$faculty || !$university || !$city) {
                continue;
            }

            $owner = $users->random();

            $group = Group::create([
                'groups_name' => fake()->words(2, true),
                'user_id' => $owner->id,
                'city_id' => $city->id,
                'university_id' => $university->id,
                'faculty_id' => $faculty->id,
                'department_id' => $department->id,
                'class_models_id' => $classModel->id,
            ]);

            $studentIds = $users
                ->where('id', '!=', $owner->id)
                ->pluck('id')
                ->shuffle()
                ->take(random_int(3, min(8, $users->count() - 1)));

            if ($studentIds->isNotEmpty()) {
                $group->students()->syncWithoutDetaching($studentIds);
            }
        }
    }
}
