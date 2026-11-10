<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;

class ScholarshipDetailController extends Controller
{
    // PBI-20: detail beasiswa. PBI-21: persyaratan, dokumen wajib, periode pendaftaran.
    public function show(Scholarship $scholarship)
    {
        // draft tidak boleh dilihat mahasiswa; data yang di-soft-delete otomatis 404
        abort_if($scholarship->status !== 'published', 404);

        $scholarship->load(['requirement', 'requiredDocuments']);

        return view('student.scholarships.show', [
            'scholarship'   => $scholarship,
            'periodeStatus' => $this->periodeStatus($scholarship),
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
}