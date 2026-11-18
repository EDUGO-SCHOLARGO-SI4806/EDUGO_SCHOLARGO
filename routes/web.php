<?php

use App\Http\Controllers\Admin\ScholarshipController;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\ScholarshipRequirementController;
use App\Http\Controllers\Student\ScholarshipDetailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ScholarGO
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('admin.scholarships.index');
});

// Admin Routes (PBI Mitra & Admin)
Route::prefix('admin')->name('admin.')->group(function () {
    // Rute Tempat Sampah & Pemulihan (Harus di atas Route::resource agar tidak tertimpa {scholarship})
    Route::get('scholarships/trashed', [ScholarshipController::class, 'trashed'])->name('scholarships.trashed');
    Route::post('scholarships/{id}/restore', [ScholarshipController::class, 'restore'])->name('scholarships.restore');
    Route::delete('scholarships/{id}/force-delete', [ScholarshipController::class, 'forceDelete'])->name('scholarships.forceDelete');

    Route::resource('scholarships', ScholarshipController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
});

// FR-05 Kelola Persyaratan Beasiswa (PBI-13, PBI-14, & PBI-15) - Portal Mitra
Route::prefix('admin')->group(function () {
    Route::get('/requirements', [ScholarshipRequirementController::class, 'index'])->name('requirements.index');
    Route::get('/requirements/{id}/edit', [ScholarshipRequirementController::class, 'edit'])->name('requirements.edit');
    Route::put('/requirements/{id}', [ScholarshipRequirementController::class, 'update'])->name('requirements.update');

    // PBI-14 (ED-14) - Menentukan Daftar Dokumen Wajib per Beasiswa
    Route::get('/requirements/{id}/documents', [ScholarshipRequirementController::class, 'documents'])->name('requirements.documents');
    Route::post('/requirements/{id}/documents', [ScholarshipRequirementController::class, 'storeDocument'])->name('requirements.documents.store');
    Route::patch('/requirements/{id}/documents/{docId}/toggle', [ScholarshipRequirementController::class, 'toggleDocumentMandatory'])->name('requirements.documents.toggle');
    Route::delete('/requirements/{id}/documents/{docId}', [ScholarshipRequirementController::class, 'destroyDocument'])->name('requirements.documents.destroy');

    // PBI-15 - Mengatur Periode dan Batas Waktu Pendaftaran
    Route::get('/requirements/{id}/period', [ScholarshipRequirementController::class, 'period'])->name('requirements.period');
    Route::put('/requirements/{id}/period', [ScholarshipRequirementController::class, 'updatePeriod'])->name('requirements.period.update');
    Route::patch('/requirements/{id}/period/toggle', [ScholarshipRequirementController::class, 'togglePeriod'])->name('requirements.period.toggle');
});

// Student
Route::get('/scholarships/{scholarship}', [ScholarshipDetailController::class, 'show'])
    ->name('student.scholarships.show');

// Tampilan Publik Persyaratan Mahasiswa (Mockup Gambar 26)
Route::get('/scholarships/{id}/persyaratan', [ScholarshipRequirementController::class, 'show'])->name('scholarships.requirements');

Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa.index');

require __DIR__.'/admin.php';
