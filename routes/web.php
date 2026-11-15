<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileDocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PBI-07: Lihat Profil
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

// PBI-08: Edit & Update Data Akademik
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

// PBI-09: Upload Dokumen Profil
Route::get('/profile/documents', [ProfileDocumentController::class, 'index'])->name('profile.documents');
Route::post('/profile/documents', [ProfileDocumentController::class, 'store'])->name('profile.documents.store');
Route::get('/profile/documents/{document}/download', [ProfileDocumentController::class, 'download'])->name('profile.documents.download');
Route::delete('/profile/documents/{document}', [ProfileDocumentController::class, 'destroy'])->name('profile.documents.destroy');
