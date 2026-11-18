<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizerDocumentController;
use App\Http\Middleware\EnsureUserIsOrganizer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileDocumentController;
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

// Beranda: Jika sudah login ➔ ke portal dokumen, jika belum ➔ ke halaman login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('organizer.documents.index');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Autentikasi Pengguna (Login, Register, Logout)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| FR-01 - Pengumpulan Bukti Legalitas Penyelenggara (Sprint 1 - Salsabila)
|--------------------------------------------------------------------------
| PBI-01 unggah dokumen | PBI-02 validasi | PBI-03 status & unggah ulang
*/
Route::prefix('organizer')
    ->name('organizer.')
    ->middleware(['auth', EnsureUserIsOrganizer::class])
    ->group(function () {
        Route::get('/documents', [OrganizerDocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/upload', [OrganizerDocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents/upload', [OrganizerDocumentController::class, 'store'])->name('documents.store');
        Route::put('/documents/resubmit', [OrganizerDocumentController::class, 'resubmit'])->name('documents.resubmit');
        Route::get('/documents/{document}/download', [OrganizerDocumentController::class, 'download'])->name('documents.download');
    });

/*
|--------------------------------------------------------------------------
| Login Pintas Dev untuk Demo (Environment "local")
|--------------------------------------------------------------------------
| Memudahkan demo langsung 4 status penyelenggara tanpa ketik password
*/
if (app()->environment('local')) {
    Route::get('/dev/login/{state}', function (Request $request, string $state) {
        $user = User::where('email', "penyelenggara.{$state}@scholargo.test")->firstOrFail();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('organizer.documents.index');
    })->whereIn('state', ['baru', 'menunggu', 'ditolak', 'disetujui'])->name('dev.login');
}
Route::get('/', function () {
    return redirect()->route('admin.scholarships.index');
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
