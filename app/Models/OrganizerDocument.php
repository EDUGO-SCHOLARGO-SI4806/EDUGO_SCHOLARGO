<?php

namespace App\Models;

use App\Enums\OrganizerDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Satu dokumen legalitas milik satu penyelenggara.
 * Satu jenis dokumen hanya punya satu file aktif (unggah ulang = mengganti).
 */
class OrganizerDocument extends Model
{
    /** Batas ukuran file dalam KB (2048 KB = 2 MB). */
    public const MAX_SIZE_KB = 2048;

    /** Ekstensi yang diperbolehkan (PBI-02). */
    public const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg'];

    protected $fillable = [
        'organizer_profile_id',
        'type',
        'original_name',
        'path',
        'mime_type',
        'size_bytes',
    ];

    protected function casts(): array
    {
        return [
            'type' => OrganizerDocumentType::class,
            'size_bytes' => 'integer',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(OrganizerProfile::class, 'organizer_profile_id');
    }

    public function sizeForHumans(): string
    {
        $kb = $this->size_bytes / 1024;

        if ($kb >= 1024) {
            return number_format($kb / 1024, 2, ',', '.').' MB';
        }

        return number_format($kb, 0, ',', '.').' KB';
    }
}
