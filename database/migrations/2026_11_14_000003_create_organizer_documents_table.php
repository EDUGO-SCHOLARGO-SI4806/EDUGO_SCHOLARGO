<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_profile_id')->constrained('organizer_profiles')->cascadeOnDelete();

            // akta_pendirian | sk_pengesahan | nib_npwp | surat_kuasa | identitas_perwakilan
            $table->string('type', 40);
            $table->string('original_name');
            $table->string('path');
            $table->string('mime_type', 100);
            $table->unsignedInteger('size_bytes');
            $table->timestamps();

            // Satu jenis dokumen = satu file aktif; unggah ulang menggantinya.
            $table->unique(['organizer_profile_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_documents');
    }
};
