<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data dummy untuk testing Manajemen Akun Super Admin (FR-02).
 * Jalankan: php artisan db:seed --class=AccountSeeder
 * Aman dijalankan berulang (berdasarkan email). Password semua akun: password
 *
 * Akun penyelenggara (beserta profil dan dokumennya) dibuat oleh
 * OrganizerDemoSeeder milik FR-01 (Salsabila), jadi dipanggil dari sini.
 */
class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['Super Admin', 'superadmin@scholargo.test', User::ROLE_SUPER_ADMIN],

            ['Admin Satu', 'admin1@scholargo.test', User::ROLE_ADMIN],
            ['Admin Dua', 'admin2@scholargo.test', User::ROLE_ADMIN],

            ['Budi Santoso', 'budi@mahasiswa.test', User::ROLE_STUDENT],
            ['Citra Lestari', 'citra@mahasiswa.test', User::ROLE_STUDENT],
            ['Dimas Pratama', 'dimas@mahasiswa.test', User::ROLE_STUDENT],
            ['Eka Putri', 'eka@mahasiswa.test', User::ROLE_STUDENT],
            ['Fajar Nugroho', 'fajar@mahasiswa.test', User::ROLE_STUDENT],
            ['Gita Anggraini', 'gita@mahasiswa.test', User::ROLE_STUDENT],
            ['Hendra Wijaya', 'hendra@mahasiswa.test', User::ROLE_STUDENT],
            ['Indah Permata', 'indah@mahasiswa.test', User::ROLE_STUDENT],
        ];

        foreach ($accounts as [$name, $email, $role]) {
            User::firstOrNew(['email' => $email])->forceFill([
                'name' => $name,
                'password' => 'password',
                'email_verified_at' => now(),
                'role' => $role,
            ])->save();
        }

        $this->call(OrganizerDemoSeeder::class);
    }
}