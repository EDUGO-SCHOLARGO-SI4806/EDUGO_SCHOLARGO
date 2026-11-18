<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for PBI-14 (ED-14) - Menentukan Daftar Dokumen Wajib per Beasiswa
     */
    public function up(): void
    {
        Schema::create('required_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained('scholarships')->onDelete('cascade');
            $table->string('document_name'); // Nama dokumen (Contoh: Transkrip Nilai, KTP, Surat Rekomendasi)
            $table->string('description')->nullable(); // Keterangan tambahan berkas untuk mahasiswa
            $table->string('file_format')->default('PDF, JPG'); // Format berkas yang diizinkan (NFR-05: maks 2 MB)
            $table->boolean('is_mandatory')->default(true); // Status dokumen: true = Wajib, false = Opsional/Pendukung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('required_documents');
    }
};
