<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        if (University::count() > 0) {
            return;
        }

        $cities = City::all();
        if ($cities->isEmpty()) {
            return;
        }

        foreach ($cities as $city) {
            University::factory()
                ->count(2)
                ->create(['city_id' => $city->id]);
        }
    }
}
