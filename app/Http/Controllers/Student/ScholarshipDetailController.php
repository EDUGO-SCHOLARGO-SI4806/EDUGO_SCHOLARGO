<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\StudentProfile;
use App\Services\EligibilityService;
use Illuminate\Http\Request;

class ScholarshipDetailController extends Controller
{
    // PBI-20: detail. PBI-21: syarat, dokumen, periode. PBI-22: cek kesesuaian profil.
    public function show(Request $request, Scholarship $scholarship, EligibilityService $checker)
    {
        // draft tidak boleh dilihat mahasiswa; data yang di-soft-delete otomatis 404
        abort_if($scholarship->status !== 'published', 404);

        $scholarship->load(['requirement', 'requiredDocuments', 'registrationPeriod']);

        // Evaluasi hanya profil mahasiswa yang sedang login. Jangan pernah memakai profil
        // user lain sebagai fallback untuk pengunjung atau role selain mahasiswa.
        $user = $request->user();
        $eligibility = $user?->role === 'mahasiswa'
            ? $checker->check(StudentProfile::where('user_id', $user->id)->first(), $scholarship)
            : null;

        return view('student.scholarships.show', [
            'scholarship'   => $scholarship,
            'periodeStatus' => $this->periodeStatus($scholarship),
            'eligibility'   => $eligibility,
        ]);
    }

    private function periodeStatus(Scholarship $scholarship): string
    {
        $startDate = $scholarship->registrationPeriod?->open_date ?? $scholarship->start_date;
        $endDate   = $scholarship->registrationPeriod?->close_date ?? $scholarship->end_date;

        if (! $startDate || ! $endDate) {
            return 'belum_ditentukan';
        }

        if ($scholarship->registrationPeriod && ! $scholarship->registrationPeriod->is_active) {
            return 'ditutup';
        }

        if (today()->lt($startDate)) {
            return 'belum_dibuka';
        }

        if (today()->gt($endDate)) {
            return 'ditutup';
        }

        return 'dibuka';
    }

}
