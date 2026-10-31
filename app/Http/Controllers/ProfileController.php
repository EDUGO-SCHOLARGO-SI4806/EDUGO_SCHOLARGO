<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * PBI-07: Menampilkan profil pengguna
     * Sebagai mahasiswa, saya ingin melihat profil saya agar tahu data yang tersimpan
     *
     * SEMENTARA: fitur login belum ada, jadi yang ditampilkan adalah user pertama di database
     * (hasil seeder). TODO: ganti dengan Auth::user() dan pasang middleware 'auth' di route
     * setelah fitur login tersedia.
     */
    public function show()
    {
        $user = User::query()->orderBy('id')->first();

        abort_if($user === null, 404, 'Belum ada data. Jalankan: php artisan migrate --seed');

        // Belum ada baris profil -> tampilkan profil kosong (tanpa menyimpan apa pun)
        $profile = $user->studentProfile ?? new StudentProfile(['user_id' => $user->id]);

        $completionPercentage = $profile->completionPercentage();

        return view('profile.show', compact('user', 'profile', 'completionPercentage'));
    }
}
