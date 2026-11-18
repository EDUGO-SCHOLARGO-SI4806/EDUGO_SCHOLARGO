<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('institution_name', 150);
            $table->string('representative_name', 100);

            // pending (menunggu) | approved (disetujui) | rejected (ditolak)
            $table->string('verification_status', 20)->default('pending')->index();
            $table->text('verification_note')->nullable();
            $table->timestamp('submitted_at')->nullable();

            // Diisi Super Admin saat meninjau (FR-02)
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_profiles');
    }
};
