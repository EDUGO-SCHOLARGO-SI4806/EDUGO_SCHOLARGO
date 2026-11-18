<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * PBI-07: Menampilkan profil pengguna
     * Sebagai mahasiswa, saya ingin melihat profil saya agar tahu data yang tersimpan
     */
    public function show()
    {
        $user = $this->currentUser();

        // Belum ada baris profil -> tampilkan profil kosong (tanpa menyimpan apa pun)
        $profile = $user->studentProfile ?? new StudentProfile(['user_id' => $user->id]);

        $completionPercentage = $profile->completionPercentage();

        return view('profile.show', compact('user', 'profile', 'completionPercentage'));
    }

    /**
     * PBI-08: Menampilkan form edit data akademik
     * Sebagai mahasiswa, saya ingin melengkapi dan mengubah data akademik
     */
    public function edit()
    {
        $user = $this->currentUser();

        $profile = $user->studentProfile ?? new StudentProfile(['user_id' => $user->id]);

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * PBI-08: Menyimpan perubahan data akademik
     * Validasi: IPK 0-4, semester 1-14, jenjang D3/D4/S1/S2/S3, tanggal lahir sebelum hari ini
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'nullable|string|max:20',
            'ipk' => 'nullable|numeric|min:0|max:4',
            'semester' => 'nullable|integer|min:1|max:14',
            'program_studi' => 'nullable|string|max:255',
            'kampus' => 'nullable|string|max:255',
            'jenjang' => 'nullable|in:D3,D4,S1,S2,S3',
            'phone' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date|before:today',
            'alamat' => 'nullable|string|max:500',
        ], [
            'ipk.numeric' => 'IPK harus berupa angka',
            'ipk.min' => 'IPK minimal 0.00',
            'ipk.max' => 'IPK maksimal 4.00',
            'semester.integer' => 'Semester harus berupa angka bulat',
            'semester.min' => 'Semester minimal 1',
            'semester.max' => 'Semester maksimal 14',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenjang.in' => 'Jenjang harus D3, D4, S1, S2, atau S3',
        ]);

        $user = $this->currentUser();

        StudentProfile::updateOrCreate(['user_id' => $user->id], $validated);

        return redirect()->route('profile.show')
            ->with('success', 'Data profil berhasil diperbarui!');
    }

    /**
     * SEMENTARA: fitur login belum ada, jadi yang dipakai adalah user pertama di database
     * (hasil seeder). TODO: ganti dengan Auth::user() dan pasang middleware 'auth' di route
     * setelah fitur login tersedia.
     */
    private function currentUser(): User
    {
        $user = User::query()->orderBy('id')->first();

        abort_if($user === null, 404, 'Belum ada data. Jalankan: php artisan migrate --seed');

        return $user;
    }
}
