<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $roles = [
            'admin',            // Full access (IT)
            'kepala_divisi',    // Kepala Departemen
            'anggota',          // Staff / Member
            'vice_project_director',
            'project_director',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create Departments
        $departments = [
            'IT',
            'Administrasi',
            'Media',
            'Sponsor',
            'Event',
            'Finance',
            'Mentor',
            'Program',
            'SDM',
        ];

        foreach ($departments as $deptName) {
            Department::firstOrCreate(['name' => $deptName]);
        }

        // Create Users
        // 1. Admin (Dept IT)
        User::firstOrCreate(
            ['email' => 'admin@mfls.com'],
            [
                'name' => 'Admin IT',
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'admin')->first()->id,
                'department_id' => Department::where('name', 'IT')->first()->id,
            ]
        );

        // 2. Kepala Departemen Administrasi
        User::firstOrCreate(
            ['email' => 'admin.dept@mfls.com'],
            [
                'name' => 'Admin Dept',
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'kepala_divisi')->first()->id,
                'department_id' => Department::where('name', 'Administrasi')->first()->id,
            ]
        );

        // 3. Kepala Departemen Media
        User::firstOrCreate(
            ['email' => 'media.lead@mfls.com'],
            [
                'name' => 'Media Leader',
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'kepala_divisi')->first()->id,
                'department_id' => Department::where('name', 'Media')->first()->id,
            ]
        );

        // 4. Anggota Media
        User::firstOrCreate(
            ['email' => 'media.member@mfls.com'],
            [
                'name' => 'Media Staff',
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'anggota')->first()->id,
                'department_id' => Department::where('name', 'Media')->first()->id,
            ]
        );

        // Setup Approval Levels (VPD & PD)
        User::firstOrCreate(
            ['email' => 'vpd@mfls.com'],
            [
                'name' => 'Vice Project Director',
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'vice_project_director')->first()->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'pd@mfls.com'],
            [
                'name' => 'Project Director',
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'project_director')->first()->id,
            ]
        );

        DB::table('approval_levels')->insertOrIgnore([
            ['level_order' => 1, 'role_id' => Role::where('name', 'vice_project_director')->first()->id, 'created_at' => now(), 'updated_at' => now()],
            ['level_order' => 2, 'role_id' => Role::where('name', 'project_director')->first()->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
