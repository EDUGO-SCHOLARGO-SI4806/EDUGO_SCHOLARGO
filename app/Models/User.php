<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
        public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MAHASISWA = 'mahasiswa';
    public const ROLE_PENYELENGGARA = 'penyelenggara';

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';

    public const ROLE_LABELS = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_MAHASISWA => 'Mahasiswa',
        self::ROLE_PENYELENGGARA => 'Penyelenggara',
    ];

    public const STATUS_LABELS = [
        self::STATUS_MENUNGGU => 'Menunggu',
        self::STATUS_DISETUJUI => 'Disetujui',
        self::STATUS_DITOLAK => 'Ditolak',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst((string) $this->role);
    }

    public function verificationLabel(): string
    {
        return self::STATUS_LABELS[$this->verification_status] ?? '-';
    }
        public function roleBadgeClass(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'bg-purple-100 text-purple-700',
            self::ROLE_ADMIN => 'bg-blue-100 text-blue-700',
            self::ROLE_PENYELENGGARA => 'bg-amber-100 text-amber-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function statusBadgeClass(): ?string
    {
        return match ($this->verification_status) {
            self::STATUS_MENUNGGU => 'bg-yellow-100 text-yellow-800',
            self::STATUS_DISETUJUI => 'bg-green-100 text-green-700',
            self::STATUS_DITOLAK => 'bg-red-100 text-red-700',
            default => null,
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
}
