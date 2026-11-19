<?php

namespace Database\Seeders;

use App\Models\Beasiswa;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use Illuminate\Database\Seeder;

class AcademicMeritScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $beasiswa = Beasiswa::firstOrCreate(
            ['nama' => 'Beasiswa Prestasi Akademik'],
            [
                'penyelenggara'   => 'Yayasan Cendekia Indonesia',
                'jenjang'         => 'S1',
                'lokasi'          => 'Dalam negeri',
                'jenis_pendanaan' => 'Pendanaan Parsial',
                'deskripsi'       => 'Bantuan pendidikan bagi mahasiswa S1 aktif yang memiliki prestasi akademik baik.',
                'kategori'        => 'Prestasi akademik',
                'nominal'         => 5000000,
                'kuota'           => 50,
                'status'          => 'published',
            ]
        );

        // Pastikan record katalog pendukung memakai ID beasiswa yang sama.
        $scholarship = Scholarship::updateOrCreate(
            ['id' => $beasiswa->id],
            [
                'name'        => $beasiswa->nama,
                'organizer'   => $beasiswa->penyelenggara,
                'description' => $beasiswa->deskripsi,
                'level'       => $beasiswa->jenjang,
                'category'    => $beasiswa->kategori,
                'status'      => $beasiswa->status,
                'start_date'  => $beasiswa->tanggal_buka,
                'end_date'    => $beasiswa->tanggal_tutup,
            ]
        );

        ScholarshipRequirement::updateOrCreate(
            ['scholarship_id' => $scholarship->id],
            [
                'min_gpa'      => 3.25,
                'min_semester' => 3,
            ]
        );
    }
}
