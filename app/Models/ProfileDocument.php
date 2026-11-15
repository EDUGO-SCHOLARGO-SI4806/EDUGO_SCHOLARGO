<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ProfileDocument
 * PBI-09: Dokumen profil mahasiswa (KTM, transkrip, foto)
 * Relasi many-to-1 dengan User
 */
class ProfileDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'original_name',
        'file_path',
        'file_extension',
        'file_size',
    ];

    /**
     * Label tipe dokumen dalam Bahasa Indonesia
     */
    public const DOCUMENT_TYPES = [
        'ktm' => 'Kartu Tanda Mahasiswa (KTM)',
        'transkrip' => 'Transkrip Nilai',
        'foto' => 'Foto Profil',
        'ktp' => 'Kartu Tanda Penduduk (KTP)',
        'lainnya' => 'Dokumen Lainnya',
    ];

    /**
     * Format yang diizinkan
     */
    public const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png'];

    /**
     * Ukuran max file (2MB dalam bytes)
     */
    public const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Getter: Label tipe dokumen
     */
    public function getDocumentTypeLabelAttribute(): string
    {
        return self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type;
    }

    /**
     * Getter: Ukuran file yang diformat
     */
    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }
}
