<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Status verifikasi penyelenggara sekarang disimpan di tabel organizer_profiles
     * (FR-01, Salsabila), jadi kolom di tabel users tidak dipakai lagi.
     */
    public function up(): void
    {
        foreach (['verification_status', 'verification_note'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                Schema::table('users', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'verification_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('verification_status')->nullable()->after('role');
            });
        }
    }
};