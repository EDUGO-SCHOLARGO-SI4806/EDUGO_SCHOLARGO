<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom yang dibutuhkan oleh fitur Fira (PBI-20, PBI-21, PBI-22)
     * ke tabel scholarships yang awalnya hanya punya name, organizer, description.
     */
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            if (!Schema::hasColumn('scholarships', 'level')) {
                $table->string('level')->nullable()->after('organizer');
            }
            if (!Schema::hasColumn('scholarships', 'category')) {
                $table->string('category', 100)->nullable()->after('level');
            }
            if (!Schema::hasColumn('scholarships', 'status')) {
                $table->enum('status', ['draft', 'published'])->default('published')->after('description');
            }
            if (!Schema::hasColumn('scholarships', 'start_date')) {
                $table->date('start_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('scholarships', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('scholarships', 'organizer_name')) {
                // organizer_name adalah alias untuk organizer — kita simpan sebagai virtual via accessor
                // Tidak perlu kolom baru, sudah handle lewat accessor di model
            }
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn(['level', 'category', 'status', 'start_date', 'end_date']);
        });
    }
};
