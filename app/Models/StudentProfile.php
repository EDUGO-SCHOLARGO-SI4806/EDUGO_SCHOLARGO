<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model StudentProfile
 * PBI-07 & PBI-08: Data profil akademik mahasiswa
 * Relasi 1-to-1 dengan User
 */
class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ipk',
        'semester',
        'program_studi',
        'kampus',
        'nim',
        'jenjang',
        'phone',
        'tanggal_lahir',
        'alamat',
    ];

    protected function casts(): array
    {
        return [
            'ipk' => 'decimal:2',
            'semester' => 'integer',
            'tanggal_lahir' => 'date',
        ];
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah profil sudah lengkap
     */
    public function isComplete(): bool
    {
        return !empty($this->ipk)
            && !empty($this->semester)
            && !empty($this->program_studi)
            && !empty($this->kampus);
    }

    /**
     * Hitung persentase kelengkapan profil
     */
    public function completionPercentage(): int
    {
        $fields = ['ipk', 'semester', 'program_studi', 'kampus', 'nim', 'jenjang', 'phone', 'tanggal_lahir', 'alamat'];
        $filled = 0;

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filled++;
            }
        }

        return (int) round(($filled / count($fields)) * 100);
    }
}
