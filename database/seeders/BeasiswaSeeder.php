<?php

namespace Database\Seeders;

use App\Models\Beasiswa;
use Illuminate\Database\Seeder;

class BeasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Global Education Foundation', 'Beasiswa Global Leaders 2026',  'S1',     'Dalam negeri', -1],
            ['Yayasan Cendekia Indonesia',  'Beasiswa Cendekia Nusantara',   'S1, S2', 'Dalam negeri', 25],
            ['NusaTech Foundation',         'Future Innovators Scholarship', 'S1',     'Dalam negeri', 30],
            ['Merdeka Education',           'Beasiswa Pendidikan Merdeka',   'D4, S1', 'Dalam negeri', 70],
            ['Green Future Indonesia',      'Beasiswa Green Generation',     'S1',     'Dalam negeri', 30],
            ['Digital Indonesia Foundation','Beasiswa Talenta Digital',      'S1, S2', 'Teknologi',    34],
        ];

        foreach ($data as [$penyelenggara, $nama, $jenjang, $lokasi, $hari]) {
            Beasiswa::create([
                'nama'            => $nama,
                'penyelenggara'   => $penyelenggara,
                'jenjang'         => $jenjang,
                'lokasi'          => $lokasi,
                'jenis_pendanaan' => 'Pendanaan Penuh',
                'deskripsi'       => 'Program beasiswa untuk mahasiswa berprestasi.',
                'tanggal_tutup'   => now()->addDays($hari),
            ]);
        }

        Beasiswa::factory()->count(4)->create();
    }
}