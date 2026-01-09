<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $target = 15;
        $missing = max(0, $target - User::count());

        if ($missing === 0) {
            return;
        }

        User::factory()->count($missing)->create();
    }
}
