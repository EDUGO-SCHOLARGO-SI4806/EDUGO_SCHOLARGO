<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PBI 15 - Mengatur Periode dan Batas Waktu Pendaftaran
     * Migration untuk membuat tabel registration_periods
     */
    public function up(): void
    {
        Schema::create('registration_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained('scholarships')->onDelete('cascade');
            $table->date('open_date')->comment('Tanggal pembukaan pendaftaran');
            $table->date('close_date')->comment('Tanggal penutupan/batas waktu pendaftaran');
            $table->boolean('is_active')->default(true)->comment('Status aktif periode pendaftaran');
            $table->string('description')->nullable()->comment('Keterangan tambahan periode pendaftaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_periods');
    }
};
