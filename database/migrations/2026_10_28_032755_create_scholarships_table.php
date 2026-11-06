<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('penyelenggara');
            $table->text('deskripsi')->nullable();
            $table->string('jenjang');
            $table->string('lokasi')->nullable();
            $table->string('jenis_pendanaan')->nullable();
            $table->string('kategori', 100)->nullable();
            $table->unsignedBigInteger('nominal')->nullable();
            $table->unsignedInteger('kuota')->nullable();
            $table->date('tanggal_buka')->nullable();
            $table->date('tanggal_tutup')->nullable();
            $table->string('link_sumber')->nullable();
            $table->string('poster')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beasiswas');
    }
};
