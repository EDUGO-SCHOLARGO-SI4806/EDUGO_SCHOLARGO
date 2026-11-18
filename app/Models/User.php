<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Nilai kolom `role`. Semua anggota tim sebaiknya memakai konstanta ini.
    // Nilai kolom `role`. Semua anggota tim memakai konstanta ini.
    public const ROLE_STUDENT = 'mahasiswa';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ORGANIZER = 'penyelenggara';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public const ROLE_LABELS = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_STUDENT => 'Mahasiswa',
        self::ROLE_ORGANIZER => 'Penyelenggara',
    ];

    // Status verifikasi disimpan di tabel organizer_profiles (milik FR-01, Salsabila).
    public const STATUS_LABELS = [
        OrganizerProfile::STATUS_PENDING => 'Menunggu',
        OrganizerProfile::STATUS_APPROVED => 'Disetujui',
        OrganizerProfile::STATUS_REJECTED => 'Ditolak',
    ];

    public function organizerProfile(): HasOne
    {
        return $this->hasOne(OrganizerProfile::class);
    }

    public function isOrganizer(): bool
    {
        return $this->role === self::ROLE_ORGANIZER;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst((string) $this->role);
    }

    public function roleBadgeClass(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'bg-purple-100 text-purple-700',
            self::ROLE_ADMIN => 'bg-blue-100 text-blue-700',
            self::ROLE_ORGANIZER => 'bg-amber-100 text-amber-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Label status verifikasi. Hanya berlaku untuk penyelenggara.
     */
    public function verificationLabel(): string
    {
        if (! $this->isOrganizer()) {
            return '-';
        }

        $status = $this->organizerProfile?->verification_status;

        return $status ? (self::STATUS_LABELS[$status] ?? $status) : 'Belum mengajukan';
    }

    /**
     * Warna badge status verifikasi (null = tidak ditampilkan, mis. mahasiswa/admin).
     */
    public function statusBadgeClass(): ?string
    {
        if (! $this->isOrganizer()) {
            return null;
        }

        return match ($this->organizerProfile?->verification_status) {
            OrganizerProfile::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            OrganizerProfile::STATUS_APPROVED => 'bg-green-100 text-green-700',
            OrganizerProfile::STATUS_REJECTED => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organizerProfile(): HasOne
    {
        return $this->hasOne(OrganizerProfile::class);
    }

    public function isOrganizer(): bool
    {
        return $this->role === self::ROLE_ORGANIZER;
    }
}
    /**
     * Relasi ke StudentProfile (1-to-1)
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    /**
     * Relasi ke ProfileDocument (1-to-many)
     */
    public function profileDocuments(): HasMany
    {
        return $this->hasMany(ProfileDocument::class);
    }
}
