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

// FR-05 Kelola Persyaratan Beasiswa (PBI-13 & PBI-14) - Portal Mitra
Route::prefix('admin')->group(function () {
    Route::get('/requirements', [ScholarshipRequirementController::class, 'index'])->name('requirements.index');
    Route::get('/requirements/{id}/edit', [ScholarshipRequirementController::class, 'edit'])->name('requirements.edit');
    Route::put('/requirements/{id}', [ScholarshipRequirementController::class, 'update'])->name('requirements.update');

    // PBI-14 (ED-14) - Menentukan Daftar Dokumen Wajib per Beasiswa
    Route::get('/requirements/{id}/documents', [ScholarshipRequirementController::class, 'documents'])->name('requirements.documents');
    Route::post('/requirements/{id}/documents', [ScholarshipRequirementController::class, 'storeDocument'])->name('requirements.documents.store');
    Route::patch('/requirements/{id}/documents/{docId}/toggle', [ScholarshipRequirementController::class, 'toggleDocumentMandatory'])->name('requirements.documents.toggle');
    Route::delete('/requirements/{id}/documents/{docId}', [ScholarshipRequirementController::class, 'destroyDocument'])->name('requirements.documents.destroy');
});

// Tampilan Publik Persyaratan Mahasiswa (Mockup Gambar 26)
Route::get('/scholarships/{id}/persyaratan', [ScholarshipRequirementController::class, 'show'])->name('scholarships.requirements');
