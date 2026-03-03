<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class PengurusSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengurus = [
            ['Dioniyoga Pratama', 'Project Director'],
            ['Khaira Annisa Permana', 'Vice Project Director I & II'],
            ['Siti Meilani', 'Ka. Departemen Administrasi Data Evaluation & Reporting'],
            ['Moza Aditiya', 'Staff Departemen Administrasi Data Evaluation & Reporting'],
            ['Silvi Melani Putri', 'Staff Departemen Administrasi Data Evaluation & Reporting'],
            ['Kayla Allysa Putri Zaskia', 'Ka. Departemen Finance & Budget Control'],
            ['Alfiah Wirda Lukmansyah', 'Staff Departemen Finance & Budget Control'],
            ['Racheel Elisabeth', 'Staff Departemen Finance & Budget Control'],
            ['Irwan Maulana Yusup', 'Ka. Departemen Program, Event, & Akademik'],
            ['Fadillah Juniyardiana', 'Staff Departemen Program, Event, & Akademik'],
            ['Ludovika Ronauli Sitompul', 'Staff Departemen Program, Event, & Akademik'],
            ['Queentia Aster', 'Staff Departemen Program, Event, & Akademik'],
            ['Innez Putria Abidin', 'Staff Departemen Program, Event, & Akademik'],
            ['Rini Mandayani', 'Staff Departemen Program, Event, & Akademik'],
            ['Ferdi Almansah', 'Ka. Departemen Event Operations Logistics & Hospitality'],
            ['Alya Salsabilla', 'Staff Departemen Event Operations Logistics & Hospitality'],
            ['Farid Palasara', 'Staff Departemen Event Operations Logistics & Hospitality'],
            ['Joshua Adriel Andrenna', 'Staff Departemen Event Operations Logistics & Hospitality'],
            ['Kirania Rizma Az-Zahra', 'Staff Departemen Event Operations Logistics & Hospitality'],
            ['Muhamad Rifatur Seva Kuswara', 'Staff Departemen Event Operations Logistics & Hospitality'],
            ['Irma Listya', 'Ka. Departemen Partnership, Sponsorship, & External Relations'],
            ['Hilda Aenusyifa', 'Staff Departemen Partnership, Sponsorship, & External Relations'],
            ['Fifi Oktafia', 'Staff Departemen Partnership, Sponsorship, & External Relations'],
            ['Wildan Satria', 'Staff Departemen Partnership, Sponsorship, & External Relations'],
            ['Gracia Lamtiar Yosefine Sagala', 'Staff Departemen Partnership, Sponsorship, & External Relations'],
            ['Irfan Syahfutra', 'Staff Departemen Partnership, Sponsorship, & External Relations'],
            ['Violin Artaria Silalahi', 'Ka. Departemen Media, Branding, & Public Relations'],
            ['Naya Novi Sadila', 'Staff Departemen Media, Branding, & Public Relations'],
            ['Wulan Azalia Putri Andriani', 'Staff Departemen Media, Branding, & Public Relations'],
            ['Gilang Aditia Kumala', 'Staff Departemen Media, Branding, & Public Relations'],
            ['Rahmalia Putri', 'Staff Departemen Media, Branding, & Public Relations'],
            ['Cellindia Vanesa Heriyandu', 'Staff Departemen Media, Branding, & Public Relations'],
            ['Safina Nur Lailatun Nuha', 'Ka. Departemen Mentoring & Participant Experience'],
            ['Nabila Novebrianti', 'Staff Departemen Mentoring & Participant Experience'],
            ['Zuhrio Ghamdi Salasa', 'Staff Departemen Mentoring & Participant Experience'],
            ['Salma Maesyaroh', 'Staff Departemen Mentoring & Participant Experience'],
            ['Risky Zuliansyah', 'Staff Departemen Mentoring & Participant Experience'],
            ['Sandy Arif Saputra', 'Staff Departemen Mentoring & Participant Experience'],
            ['Syahid Ahmad Fauzan', 'Ka. Departemen Regional & Outreach'],
            ['Cinta Cantika', 'Staff Departemen Regional & Outreach'],
            ['Kenneth Sulthan Arsenio', 'Staff Departemen Regional & Outreach'],
            ['Ibrahimoavic', 'Staff Departemen Regional & Outreach'],
            ['Rizki', 'Staff Departemen Regional & Outreach'],
            ['Andini Raissa', 'Staff Departemen Regional & Outreach'],
            ['Muhammad Erlangga Putra Witanto', 'Ka. Departemen Information Technology, AI, & Digital Innovation'],
            ['Gavino Pasha Putra', 'Staff Departemen Information Technology, AI, & Digital Innovation'],
            ['Dzakiyah Febriyanti', 'Staff Departemen Information Technology, AI, & Digital Innovation'],
            ['Satria Pambudi Attur Rohman', 'Staff Departemen Information Technology, AI, & Digital Innovation'],
            ['Aqila Rakan Ramdahnil', 'Staff Departemen Information Technology, AI, & Digital Innovation'],
        ];

        foreach ($pengurus as $data) {
            $nama = $data[0];
            $jabatan = strtolower($data[1]);

            // Tentukan role
            if (str_contains($jabatan, 'project director') && !str_contains($jabatan, 'vice')) {
                $roleName = 'project_director';
            } elseif (str_contains($jabatan, 'vice project director')) {
                $roleName = 'vice_project_director';
            } elseif (str_contains($jabatan, 'ka.')) {
                $roleName = 'kepala_divisi';
            } else {
                $roleName = 'anggota';
            }

            $role = Role::where('name', $roleName)->first();

            // determine department from jabatan text if possible (scan whole jabatan for keywords)
            $departmentId = null;
            $keywords = [
                // map keywords to full department names (order matters)
                'information technology' => 'Departemen Information Technology, AI, & Digital Innovation',
                'digital' => 'Departemen Information Technology, AI, & Digital Innovation',
                'technology' => 'Departemen Information Technology, AI, & Digital Innovation',
                'it' => 'Departemen Information Technology, AI, & Digital Innovation',

                'program' => 'Departemen Program, Event, & Akademik',
                'akademik' => 'Departemen Program, Event, & Akademik',
                'regional' => 'Departemen Regional & Outreach',
                'outreach' => 'Departemen Regional & Outreach',

                'event operations' => 'Departemen Event Operations Logistics & Hospitality',
                'operations' => 'Departemen Event Operations Logistics & Hospitality',
                'logistics' => 'Departemen Event Operations Logistics & Hospitality',
                'hospitality' => 'Departemen Event Operations Logistics & Hospitality',

                'administrasi' => 'Departemen Administrasi Data Evaluation & Reporting',
                'administration' => 'Departemen Administrasi Data Evaluation & Reporting',

                'finance' => 'Departemen Finance & Budget Control',
                'budget' => 'Departemen Finance & Budget Control',

                'partnership' => 'Departemen Partnership, Sponsorship, & External Relations',
                'sponsorship' => 'Departemen Partnership, Sponsorship, & External Relations',
                'external relations' => 'Departemen Partnership, Sponsorship, & External Relations',

                'media' => 'Departemen Media, Branding, & Public Relations',
                'branding' => 'Departemen Media, Branding, & Public Relations',
                'public relations' => 'Departemen Media, Branding, & Public Relations',

                'mentoring' => 'Departemen Mentoring & Participant Experience',
                'mentor' => 'Departemen Mentoring & Participant Experience',

                'sponsor' => 'Departemen Partnership, Sponsorship, & External Relations',
                'sdm' => 'SDM',
            ];

            foreach ($keywords as $kw => $deptName) {
                if (preg_match('/\\b'.preg_quote($kw, '/').'\\b/i', $jabatan)) {
                    $departmentId = Department::where('name', $deptName)->first()?->id;
                    break;
                }
            }

            // use first name for email per request
            $firstName = explode(' ', $nama)[0];
            $email = strtolower($firstName) . '.mfls@com';

            $attributes = [
                'name' => $nama,
                'password' => Hash::make('password'),
                'role_id' => $role?->id,
            ];
            if ($departmentId) {
                $attributes['department_id'] = $departmentId;
            }

            User::firstOrCreate(['email' => $email], $attributes);
        }
    }
}
