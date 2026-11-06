<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ScholarshipController;

Route::get('/', function () {
    return redirect()->route('admin.scholarships.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Rute Tempat Sampah & Pemulihan (Harus di atas Route::resource agar tidak tertimpa {scholarship})
    Route::get('scholarships/trashed', [ScholarshipController::class, 'trashed'])->name('scholarships.trashed');
    Route::post('scholarships/{id}/restore', [ScholarshipController::class, 'restore'])->name('scholarships.restore');
    Route::delete('scholarships/{id}/force-delete', [ScholarshipController::class, 'forceDelete'])->name('scholarships.forceDelete');

    Route::resource('scholarships', ScholarshipController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
});
