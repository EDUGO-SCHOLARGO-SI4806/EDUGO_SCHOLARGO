<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['Super Admin', 'superadmin@scholargo.test', User::ROLE_SUPER_ADMIN, null],

            ['Admin Satu', 'admin1@scholargo.test', User::ROLE_ADMIN, null],
            ['Admin Dua', 'admin2@scholargo.test', User::ROLE_ADMIN, null],

            ['Budi Santoso', 'budi@mahasiswa.test', User::ROLE_MAHASISWA, null],
            ['Citra Lestari', 'citra@mahasiswa.test', User::ROLE_MAHASISWA, null],
            ['Dimas Pratama', 'dimas@mahasiswa.test', User::ROLE_MAHASISWA, null],
            ['Eka Putri', 'eka@mahasiswa.test', User::ROLE_MAHASISWA, null],
            ['Fajar Nugroho', 'fajar@mahasiswa.test', User::ROLE_MAHASISWA, null],
            ['Gita Anggraini', 'gita@mahasiswa.test', User::ROLE_MAHASISWA, null],
            ['Hendra Wijaya', 'hendra@mahasiswa.test', User::ROLE_MAHASISWA, null],
            ['Indah Permata', 'indah@mahasiswa.test', User::ROLE_MAHASISWA, null],

            ['Yayasan Cendekia', 'cendekia@penyelenggara.test', User::ROLE_PENYELENGGARA, User::STATUS_MENUNGGU],
            ['PT Maju Bersama', 'majubersama@penyelenggara.test', User::ROLE_PENYELENGGARA, User::STATUS_DISETUJUI],
            ['Lembaga Nusantara', 'nusantara@penyelenggara.test', User::ROLE_PENYELENGGARA, User::STATUS_DITOLAK],
        ];

        foreach ($accounts as [$name, $email, $role, $status]) {
            $user = User::firstOrNew(['email' => $email]);

            $user->forceFill([
                'name' => $name,
                'password' => 'password',
                'email_verified_at' => now(),
                'role' => $role,
                'verification_status' => $status,
            ])->save();
        }
    }
}