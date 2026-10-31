<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PBI-07 & PBI-08: Tabel student_profiles
     * Menyimpan data akademik mahasiswa (1-to-1 dengan users)
     */
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('ipk', 3, 2)->nullable();         // IPK (0.00 - 4.00)
            $table->integer('semester')->nullable();             // Semester saat ini
            $table->string('program_studi')->nullable();        // Program Studi
            $table->string('kampus')->nullable();                // Nama Kampus
            $table->string('nim')->nullable();                   // Nomor Induk Mahasiswa
            $table->string('jenjang')->nullable();               // S1, S2, D3, D4
            $table->string('phone')->nullable();                 // No. HP
            $table->date('tanggal_lahir')->nullable();           // Tanggal Lahir
            $table->text('alamat')->nullable();                  // Alamat
            $table->timestamps();

            $table->unique('user_id'); // Satu user satu profil
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
