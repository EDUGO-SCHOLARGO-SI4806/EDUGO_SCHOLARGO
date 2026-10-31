<?php

namespace Database\Seeders;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Data dummy untuk menguji PBI-07 (lihat profil).
     */
    public function run(): void
    {
        // Mahasiswa dengan profil lengkap
        $lengkap = User::updateOrCreate(
            ['email' => 'rafi@scholargo.test'],
            ['name' => 'Muhammad Rafi Fazlullah Pasya', 'password' => 'password'],
        );

        StudentProfile::updateOrCreate(['user_id' => $lengkap->id], [
            'nim' => '102022400170',
            'ipk' => 3.75,
            'semester' => 5,
            'program_studi' => 'Informatika',
            'kampus' => 'Telkom University',
            'jenjang' => 'S1',
            'phone' => '081234567890',
            'tanggal_lahir' => '2004-05-15',
            'alamat' => 'Bandung, Jawa Barat',
        ]);

        // Mahasiswa tanpa data profil (untuk melihat tampilan "Belum diisi")
        User::updateOrCreate(
            ['email' => 'anindya@scholargo.test'],
            ['name' => 'Anindya Zafirah', 'password' => 'password'],
        );
    }
}
