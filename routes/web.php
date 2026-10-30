<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScholarshipRequirementController;

/*
|--------------------------------------------------------------------------
| Web Routes - ScholarGO
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('requirements.index');
});

// FR-05 Kelola Persyaratan Beasiswa (PBI-13) - Portal Mitra
Route::prefix('admin')->group(function () {
    Route::get('/requirements', [ScholarshipRequirementController::class, 'index'])->name('requirements.index');
    Route::get('/requirements/{id}/edit', [ScholarshipRequirementController::class, 'edit'])->name('requirements.edit');
    Route::put('/requirements/{id}', [ScholarshipRequirementController::class, 'update'])->name('requirements.update');
});

// Tampilan Publik Persyaratan Mahasiswa (Mockup Gambar 26)
Route::get('/scholarships/{id}/persyaratan', [ScholarshipRequirementController::class, 'show'])->name('scholarships.requirements');
