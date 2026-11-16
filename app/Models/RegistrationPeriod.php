<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RegistrationPeriod
 * PBI 15 - Mengatur Periode dan Batas Waktu Pendaftaran
 *
 * Menyimpan data periode pendaftaran (tanggal buka, tanggal tutup, status aktif)
 * untuk setiap program beasiswa yang dikelola oleh admin.
 */
class RegistrationPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholarship_id',
        'open_date',
        'close_date',
        'is_active',
        'description',
    ];

    protected $casts = [
        'open_date'  => 'date',
        'close_date' => 'date',
        'is_active'  => 'boolean',
    ];

    /**
     * Relasi ke model Scholarship (Many-to-One)
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    /**
     * Mengecek apakah periode pendaftaran sedang berlangsung (terbuka)
     */
    public function isOpen(): bool
    {
        $today = now()->startOfDay();
        return $this->is_active
            && $today->greaterThanOrEqualTo($this->open_date)
            && $today->lessThanOrEqualTo($this->close_date);
    }

    /**
     * Mengecek apakah periode pendaftaran belum dimulai
     */
    public function isUpcoming(): bool
    {
        return $this->is_active && now()->startOfDay()->lessThan($this->open_date);
    }

    /**
     * Mengecek apakah periode pendaftaran sudah ditutup
     */
    public function isClosed(): bool
    {
        return !$this->is_active || now()->startOfDay()->greaterThan($this->close_date);
    }

    /**
     * Menghitung sisa hari menuju penutupan pendaftaran
     */
    public function daysRemaining(): int
    {
        if ($this->isClosed()) {
            return 0;
        }
        return max(0, (int) now()->startOfDay()->diffInDays($this->close_date, false));
    }
}
