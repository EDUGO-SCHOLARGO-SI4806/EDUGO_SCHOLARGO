<?php

namespace App\Models;

use App\Enums\OrganizerDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Data lembaga penyelenggara + status verifikasi akunnya.
 *
 * KONTRAK UNTUK FR-02 (Admin / Ishfan):
 *  - Status verifikasi: STATUS_PENDING | STATUS_APPROVED | STATUS_REJECTED
 *  - Menyetujui akun : $profile->approve($admin)
 *  - Menolak akun    : $profile->reject($admin, 'catatan penolakan')
 *  - Dokumen         : $profile->documents (relasi ke OrganizerDocument)
 */
class OrganizerProfile extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'institution_name',
        'representative_name',
        'verification_status',
        'verification_note',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OrganizerDocument::class);
    }

    public function isPending(): bool
    {
        return $this->verification_status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->verification_status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->verification_status === self::STATUS_REJECTED;
    }

    public function statusLabel(): string
    {
        return match ($this->verification_status) {
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }

    public function approve(User $reviewer): void
    {
        $this->update([
            'verification_status' => self::STATUS_APPROVED,
            'verification_note' => null,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    public function reject(User $reviewer, string $note): void
    {
        $this->update([
            'verification_status' => self::STATUS_REJECTED,
            'verification_note' => $note,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Dipanggil setelah penyelenggara mengunggah ulang dokumen (PBI-03):
     * status kembali "menunggu" dan catatan penolakan lama dibersihkan.
     */
    public function resubmit(): void
    {
        $this->update([
            'verification_status' => self::STATUS_PENDING,
            'verification_note' => null,
            'submitted_at' => now(),
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);
    }

    public function documentOfType(OrganizerDocumentType $type): ?OrganizerDocument
    {
        return $this->documents->firstWhere('type', $type);
    }
}
