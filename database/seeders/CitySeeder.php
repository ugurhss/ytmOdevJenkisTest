<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        if (City::count() > 0) {
            return;
        }

        City::factory()->count(10)->create();
    }
}
