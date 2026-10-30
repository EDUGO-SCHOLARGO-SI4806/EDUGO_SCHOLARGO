<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed sample admin user
        User::firstOrCreate(
            ['email' => 'admin@scholargo.id'],
            [
                'name' => 'Admin EduGo',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Seed 3 sample scholarships with requirements (PBI-13)
        $s1 = Scholarship::firstOrCreate(
            ['name' => 'Beasiswa Djarum Plus 2026'],
            [
                'organizer' => 'Djarum Foundation',
                'description' => 'Program beasiswa prestasi untuk mahasiswa D4/S1 seluruh perguruan tinggi mitra di Indonesia.',
            ]
        );
        ScholarshipRequirement::firstOrCreate(
            ['scholarship_id' => $s1->id],
            [
                'min_gpa' => 3.20,
                'min_semester' => 4,
            ]
        );

        $s2 = Scholarship::firstOrCreate(
            ['name' => 'Beasiswa Unggulan Kemendikbud'],
            [
                'organizer' => 'Kemendikbudristek RI',
                'description' => 'Beasiswa bagi mahasiswa berprestasi tingkat nasional maupun internasional.',
            ]
        );
        ScholarshipRequirement::firstOrCreate(
            ['scholarship_id' => $s2->id],
            [
                'min_gpa' => 3.00,
                'min_semester' => 1,
            ]
        );

        $s3 = Scholarship::firstOrCreate(
            ['name' => 'Beasiswa Bank Indonesia'],
            [
                'organizer' => 'Bank Indonesia',
                'description' => 'Dukungan finansial dan pengembangan kepemimpinan melalui komunitas GenBI.',
            ]
        );
        ScholarshipRequirement::firstOrCreate(
            ['scholarship_id' => $s3->id],
            [
                'min_gpa' => 3.25,
                'min_semester' => 3,
            ]
        );
    }
}
