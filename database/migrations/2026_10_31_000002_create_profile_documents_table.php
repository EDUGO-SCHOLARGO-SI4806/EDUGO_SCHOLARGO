<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PBI-09: Tabel profile_documents
     * Menyimpan dokumen profil mahasiswa (KTM, transkrip, foto)
     */
    public function up(): void
    {
        Schema::create('profile_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('document_type', ['ktm', 'transkrip', 'foto', 'ktp', 'lainnya']);
            $table->string('original_name');      // Nama file asli
            $table->string('file_path');           // Path file di storage
            $table->string('file_extension');      // pdf, jpg, jpeg, png
            $table->unsignedInteger('file_size');   // Ukuran file dalam bytes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_documents');
    }
};
