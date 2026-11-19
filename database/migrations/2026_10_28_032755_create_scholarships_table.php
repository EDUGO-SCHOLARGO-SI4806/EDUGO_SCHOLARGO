<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('beasiswas')) {
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
        } else {
            Schema::table('beasiswas', function (Blueprint $table) {
                if (!Schema::hasColumn('beasiswas', 'deskripsi')) {
                    $table->text('deskripsi')->nullable()->after('jenis_pendanaan');
                }
                if (!Schema::hasColumn('beasiswas', 'kategori')) {
                    $table->string('kategori', 100)->nullable()->after('jenis_pendanaan');
                }
                if (!Schema::hasColumn('beasiswas', 'nominal')) {
                    $table->unsignedBigInteger('nominal')->nullable()->after('kategori');
                }
                if (!Schema::hasColumn('beasiswas', 'kuota')) {
                    $table->unsignedInteger('kuota')->nullable()->after('nominal');
                }
                if (!Schema::hasColumn('beasiswas', 'tanggal_buka')) {
                    $table->date('tanggal_buka')->nullable()->after('kuota');
                }
                if (!Schema::hasColumn('beasiswas', 'link_sumber')) {
                    $table->string('link_sumber')->nullable()->after('tanggal_tutup');
                }
                if (!Schema::hasColumn('beasiswas', 'poster')) {
                    $table->string('poster')->nullable()->after('link_sumber');
                }
                if (!Schema::hasColumn('beasiswas', 'status')) {
                    $table->enum('status', ['draft', 'published'])->default('draft')->after('poster');
                }
                if (!Schema::hasColumn('beasiswas', 'deleted_at')) {
                    $table->softDeletes()->after('updated_at');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('beasiswas');
    }
};
