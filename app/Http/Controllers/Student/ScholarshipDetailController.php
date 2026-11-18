<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\StudentProfile;
use App\Models\User;
use App\Services\EligibilityService;
use Illuminate\Http\Request;

class ScholarshipDetailController extends Controller
{
    // PBI-20: detail. PBI-21: syarat, dokumen, periode. PBI-22: cek kesesuaian profil.
    public function show(Request $request, Scholarship $scholarship, EligibilityService $checker)
    {
        // draft tidak boleh dilihat mahasiswa; data yang di-soft-delete otomatis 404
        abort_if($scholarship->status !== 'published', 404);

        $scholarship->load(['requirement', 'requiredDocuments']);

        $user = $request->user() ?? $this->fallbackUser();

        // null kalau tidak ada user sama sekali
        $eligibility = $user
            ? $checker->check(StudentProfile::where('user_id', $user->id)->first(), $scholarship)
            : null;

        return view('student.scholarships.show', [
            'scholarship'   => $scholarship,
            'periodeStatus' => $this->periodeStatus($scholarship),
            'eligibility'   => $eligibility,
        ]);
    }

    // Satu-satunya tempat yang perlu diubah kalau PBI-16 Andika memakai kolom periode lain.
    private function periodeStatus(Scholarship $scholarship): string
    {
        if (! $scholarship->start_date || ! $scholarship->end_date) {
            return 'belum_ditentukan';
        }

        if (today()->lt($scholarship->start_date)) {
            return 'belum_dibuka';
        }

        if (today()->gt($scholarship->end_date)) {
            return 'ditutup';
        }

        return 'dibuka';
    }

    /**
     * SEMENTARA: fitur login belum ada, jadi dipakai user pertama di database
     * (sama seperti ProfileController milik Rafi).
     * TODO: hapus method ini dan pemakaiannya setelah login tersedia,
     * lalu pasang middleware 'auth' di route.
     */
    private function fallbackUser(): ?User
    {
        return User::query()->orderBy('id')->first();
    }
}