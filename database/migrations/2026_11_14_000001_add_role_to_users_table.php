<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom `role` dipakai bersama seluruh tim. Dibuat hanya jika belum ada,
     * supaya tidak bentrok kalau teman satu tim sudah menambahkannya.
     *
     * Nilai: mahasiswa | admin | penyelenggara (lihat konstanta di App\Models\User)
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('mahasiswa')->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
