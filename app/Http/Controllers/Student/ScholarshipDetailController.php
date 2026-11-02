<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;

class ScholarshipDetailController extends Controller
{
    // PBI-20: detail beasiswa (deskripsi, penyelenggara)
    public function show(Scholarship $scholarship)
    {
        // draft tidak boleh dilihat mahasiswa; data yang di-soft-delete otomatis 404
        abort_if($scholarship->status !== 'published', 404);

        return view('student.scholarships.show', compact('scholarship'));
    }
}