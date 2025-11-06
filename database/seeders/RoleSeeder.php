<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        //  Permissions
        $permissions = [
            'create Groups',
            'update Groups',
            'delete Groups',
            'show Groups',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $student    = Role::firstOrCreate(['name' => 'student']);

        // Assign permissions
        $superAdmin->syncPermissions(Permission::all());
        $admin->syncPermissions(['create Groups', 'update Groups', 'show Groups']);
        $student->syncPermissions([]); // sadece bildirim alacak

        //  Users
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $studentUser = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'),
            ]
        );

        //  Assign roles
        $superAdminUser->assignRole($superAdmin);
        $adminUser->assignRole($admin);
        $studentUser->assignRole($student);
    }
}
