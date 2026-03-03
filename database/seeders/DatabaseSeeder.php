<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            'admin', // Full access (IT)
            'kepala_divisi', // Kepala Departemen
            'anggota', // Staff / Member
            'vice_project_director',
            'project_director',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create Departments (use full official department names)
        $departments = [
            'Departemen Program, Event, & Akademik',
            'Departemen Administrasi Data Evaluation & Reporting',
            'Departemen Finance & Budget Control',
            'Departemen Event Operations Logistics & Hospitality',
            'Departemen Partnership, Sponsorship, & External Relations',
            'Departemen Media, Branding, & Public Relations',
            'Departemen Mentoring & Participant Experience',
            'Departemen Regional & Outreach',
            'Departemen Information Technology, AI, & Digital Innovation',
        ];

        foreach ($departments as $deptName) {
            Department::firstOrCreate(['name' => $deptName]);
        }

        // seed actual pengurus first (this creates Ka, VPD, PD from real data)
        $this->call(PengurusSeeders::class);

        // ensure each department has a kepala_divisi account only if PengurusSeeder didn't create one
        $kepalaRoleId = Role::where('name', 'kepala_divisi')->first()->id;
        foreach ($departments as $deptName) {
            $dept = Department::where('name', $deptName)->first();
            $hasKepala = User::where('role_id', $kepalaRoleId)
                ->where('department_id', $dept->id)
                ->exists();

            if (! $hasKepala) {
                // sanitize department name into an email-friendly local part
                $local = preg_replace('/[^a-z0-9]+/', '.', strtolower($deptName));
                $local = trim($local, '.');

                User::firstOrCreate(
                    ['email' => $local . '@mfls.com'],
                    [
                        'name' => 'Ka. Departemen '.$deptName,
                        'password' => Hash::make('password123'),
                        'role_id' => $kepalaRoleId,
                        'department_id' => $dept->id,
                    ]
                );
            }
        }

        // Create Users
        // 1. Admin (Dept IT)
        User::firstOrCreate(
        ['email' => strtolower(str_replace(' ', '.', 'Admin IT')) . '@mfls.com'],
        [
            'name' => 'Admin IT',
            'password' => Hash::make('password123'),
            'role_id' => Role::where('name', 'admin')->first()->id,
            'department_id' => Department::where('name', 'Departemen Information Technology, AI, & Digital Innovation')->first()->id,
        ]
        );

        // 2. Kepala Departemen Administrasi

        // 3. Kepala Departemen Media
     

        // Setup Approval Levels (VPD & PD) - only create generic accounts if none exist
        $vpdRoleId = Role::where('name', 'vice_project_director')->first()->id;
        $pdRoleId = Role::where('name', 'project_director')->first()->id;

        if (! User::where('role_id', $vpdRoleId)->exists()) {
            User::firstOrCreate(
                ['email' => 'vpd@mfls.com'],
                [
                    'name' => 'Vice Project Director',
                    'password' => Hash::make('password123'),
                    'role_id' => $vpdRoleId,
                ]
            );
        }

        if (! User::where('role_id', $pdRoleId)->exists()) {
            User::firstOrCreate(
                ['email' => 'pd@mfls.com'],
                [
                    'name' => 'Project Director',
                    'password' => Hash::make('password123'),
                    'role_id' => $pdRoleId,
                ]
            );
        }

        DB::table('approval_levels')->insertOrIgnore([
            ['level_order' => 1, 'role_id' => Role::where('name', 'vice_project_director')->first()->id, 'created_at' => now(), 'updated_at' => now()],
            ['level_order' => 2, 'role_id' => Role::where('name', 'project_director')->first()->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
        // Generate users from Pengurus list (already seeded earlier)
    }
}
