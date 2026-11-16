<?php

namespace Database\Seeders;

use App\Models\RegistrationPeriod;
use App\Models\RequiredDocument;
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

        // 3. Seed sample required documents per scholarship (PBI-14 / ED-14)
        $defaultDocuments = [
            [
                'document_name' => 'Transkrip Nilai Akademik',
                'description' => 'Transkrip nilai akademik semester terakhir yang disahkan oleh pihak fakultas/kampus.',
                'file_format' => 'PDF, JPG',
                'is_mandatory' => true,
            ],
            [
                'document_name' => 'Kartu Tanda Penduduk (KTP)',
                'description' => 'Salinan identitas resmi KTP elektronik yang masih berlaku dan terbaca jelas.',
                'file_format' => 'PDF, JPG',
                'is_mandatory' => true,
            ],
            [
                'document_name' => 'Surat Rekomendasi Dosen / Kampus',
                'description' => 'Surat rekomendasi resmi dari dosen pembimbing akademik atau pimpinan fakultas.',
                'file_format' => 'PDF',
                'is_mandatory' => true,
            ],
        ];

        foreach ([$s1, $s2, $s3] as $scholarship) {
            foreach ($defaultDocuments as $doc) {
                RequiredDocument::firstOrCreate(
                    [
                        'scholarship_id' => $scholarship->id,
                        'document_name' => $doc['document_name'],
                    ],
                    [
                        'description' => $doc['description'],
                        'file_format' => $doc['file_format'],
                        'is_mandatory' => $doc['is_mandatory'],
                    ]
                );
            }
        }

        // 4. Seed sample registration periods per scholarship (PBI-15)
        $periodData = [
            $s1->id => [
                'open_date'   => '2026-10-01',
                'close_date'  => '2026-12-15',
                'is_active'   => true,
                'description' => 'Periode pendaftaran gelombang utama Beasiswa Djarum Plus 2026.',
            ],
            $s2->id => [
                'open_date'   => '2026-09-15',
                'close_date'  => '2026-11-30',
                'is_active'   => true,
                'description' => 'Periode pendaftaran Beasiswa Unggulan Kemendikbud tahun ajaran 2026/2027.',
            ],
            $s3->id => [
                'open_date'   => '2026-11-01',
                'close_date'  => '2027-01-31',
                'is_active'   => true,
                'description' => 'Periode pendaftaran GenBI - Beasiswa Bank Indonesia semester genap 2026.',
            ],
        ];

        foreach ($periodData as $scholarshipId => $period) {
            RegistrationPeriod::firstOrCreate(
                ['scholarship_id' => $scholarshipId],
                $period
            );
        }
    }
}
