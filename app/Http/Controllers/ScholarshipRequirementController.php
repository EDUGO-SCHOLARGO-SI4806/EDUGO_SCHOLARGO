<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use Illuminate\Http\Request;

class ScholarshipRequirementController extends Controller
{
    /**
     * Menampilkan daftar beasiswa dan status kriteria kualifikasi (PBI-13)
     */
    public function index()
    {
        $scholarships = Scholarship::with('requirement')->get();

        return view('requirements.index', compact('scholarships'));
    }

    /**
     * Menampilkan form untuk mengatur kriteria kualifikasi beasiswa (PBI-13)
     */
    public function edit($id)
    {
        $scholarship = Scholarship::with('requirement')->findOrFail($id);
        
        $requirement = $scholarship->requirement ?? new ScholarshipRequirement([
            'scholarship_id' => $scholarship->id,
            'min_gpa' => 3.00,
            'min_semester' => 1,
        ]);

        return view('requirements.edit', compact('scholarship', 'requirement'));
    }

    /**
     * Menyimpan pembaruan kriteria kualifikasi beasiswa (PBI-13)
     */
    public function update(Request $request, $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        $validated = $request->validate([
            'min_gpa' => 'required|numeric|between:0.00,4.00',
            'min_semester' => 'required|integer|between:1,14',
        ], [
            'min_gpa.required' => 'IPK minimal wajib diisi.',
            'min_gpa.numeric' => 'IPK minimal harus berupa angka valid.',
            'min_gpa.between' => 'IPK minimal harus berada di antara 0.00 dan 4.00.',
            'min_semester.required' => 'Semester minimal wajib diisi.',
            'min_semester.integer' => 'Semester minimal harus berupa bilangan bulat.',
            'min_semester.between' => 'Semester minimal harus berada di antara semester 1 dan 14.',
        ]);

        $scholarship->requirement()->updateOrCreate(
            ['scholarship_id' => $scholarship->id],
            [
                'min_gpa' => $validated['min_gpa'],
                'min_semester' => $validated['min_semester'],
            ]
        );

        return redirect()
            ->route('requirements.index')
            ->with('success', "Kriteria kualifikasi untuk beasiswa '{$scholarship->name}' berhasil disimpan.");
    }

    /**
     * Menampilkan tampilan publik mahasiswa (Mockup Gambar 26) dengan Tab Persyaratan aktif
     */
    public function show($id)
    {
        $scholarship = Scholarship::with('requirement')->findOrFail($id);

        return view('requirements.show', compact('scholarship'));
    }
}
