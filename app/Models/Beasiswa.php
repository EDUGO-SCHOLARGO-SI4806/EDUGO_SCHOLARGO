<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Beasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'beasiswas';

    protected $fillable = [
        'nama',
        'penyelenggara',
        'jenjang',
        'lokasi',
        'jenis_pendanaan',
        'deskripsi',
        'kategori',
        'nominal',
        'kuota',
        'tanggal_buka',
        'tanggal_tutup',
        'link_sumber',
        'poster',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_buka'  => 'date',
            'tanggal_tutup' => 'date',
            'nominal'       => 'integer',
            'kuota'         => 'integer',
        ];
    }

    // Accessor: Hitung sisa hari sampai batas pendaftaran
    public function getSisaHariAttribute(): int
    {
        if (! $this->tanggal_tutup) {
            return 0;
        }

        return max(0, (int) now()->startOfDay()->diffInDays($this->tanggal_tutup->copy()->startOfDay(), false));
    }

    // Scope: Hanya beasiswa aktif / masih dibuka
    public function scopeAktif(Builder $q): Builder
    {
        return $q->where('status', 'published')
                 ->where(function ($query) {
                     // Periode khusus (termasuk penutupan manual) menjadi sumber utama.
                     // Gunakan tanggal legacy hanya jika periode khusus belum diatur.
                     $query->whereHas('registrationPeriod', function ($period) {
                         $period->where('is_active', true)
                                ->whereDate('close_date', '>=', today());
                     })->orWhere(function ($legacy) {
                         $legacy->whereDoesntHave('registrationPeriod')
                                ->where(function ($dates) {
                                    $dates->whereNull('tanggal_tutup')
                                          ->orWhereDate('tanggal_tutup', '>=', today());
                                });
                     });
                 });
    }

    // Scope: Filter pencarian (mendukung multi-kata dari origin/main)
    public function scopeFilter(Builder $q, array $f): Builder
    {
        return $q
            ->when($f['q'] ?? null, function ($query, $keyword) {
                foreach (preg_split('/\s+/', trim($keyword)) as $kata) {
                    $query->where(function ($s) use ($kata) {
                        $s->where('nama', 'like', "%{$kata}%")
                          ->orWhere('penyelenggara', 'like', "%{$kata}%")
                          ->orWhere('jenjang', 'like', "%{$kata}%")
                          ->orWhere('lokasi', 'like', "%{$kata}%")
                          ->orWhere('deskripsi', 'like', "%{$kata}%");
                    });
                }
            })
            ->when($f['batas_waktu'] ?? null, fn ($q, $v) =>
                $q->whereDate('tanggal_tutup', '<=', today()->addDays((int) $v)))
            ->when($f['jenjang'] ?? null, fn ($q, $v) => $q->where('jenjang', 'like', "%{$v}%"))
            ->when($f['pendanaan'] ?? null, function ($query, $value) {
                $legacyCategory = match ($value) {
                    'Pendanaan Penuh' => 'full scholarship',
                    'Pendanaan Parsial' => 'partial scholarship',
                    default => null,
                };

                $query->where(function ($funding) use ($value, $legacyCategory) {
                    $funding->where('jenis_pendanaan', $value);

                    if ($legacyCategory !== null) {
                        $funding->orWhereRaw('LOWER(TRIM(kategori)) = ?', [$legacyCategory]);
                    }
                });
            });
    }

    // Accessor kompatibilitas agar kode/view lama tidak error
    public function getJenisPendanaanAttribute($value): ?string
    {
        if ($value) {
            return $value;
        }

        return match (strtolower(trim((string) $this->kategori))) {
            'full scholarship' => 'Pendanaan Penuh',
            'partial scholarship' => 'Pendanaan Parsial',
            default => null,
        };
    }

    public function getNameAttribute(): ?string { return $this->nama; }
    public function getOrganizerNameAttribute(): ?string { return $this->penyelenggara; }
    public function getLevelAttribute(): ?string { return $this->jenjang; }
    public function getDescriptionAttribute(): ?string { return $this->deskripsi; }
    public function getStartDateAttribute() { return $this->tanggal_buka; }
    public function getEndDateAttribute() { return $this->tanggal_tutup; }
    public function getCategoryAttribute(): ?string { return $this->kategori; }
    public function getAmountAttribute() { return $this->nominal; }
    public function getQuotaAttribute() { return $this->kuota; }
    public function getSourceUrlAttribute(): ?string { return $this->link_sumber; }

    // Relasi ke persyaratan, dokumen, dan periode (PBI 13, 14, 15)
    public function requirement()
    {
        return $this->hasOne(ScholarshipRequirement::class, 'scholarship_id');
    }

    public function requiredDocuments()
    {
        return $this->hasMany(RequiredDocument::class, 'scholarship_id');
    }

    public function registrationPeriod()
    {
        return $this->hasOne(RegistrationPeriod::class, 'scholarship_id');
    }

    // Otomatis sinkronisasi dengan tabel scholarships untuk fitur Andika & Fira
    protected static function booted(): void
    {
        static::saved(function (self $beasiswa) {
            Scholarship::updateOrCreate(
                ['id' => $beasiswa->id],
                [
                    'name'        => $beasiswa->nama,
                    'organizer'   => $beasiswa->penyelenggara,
                    'description' => $beasiswa->deskripsi,
                    'level'       => $beasiswa->jenjang,
                    'category'    => $beasiswa->kategori,
                    'status'      => $beasiswa->status ?? 'published',
                    'start_date'  => $beasiswa->tanggal_buka,
                    'end_date'    => $beasiswa->tanggal_tutup,
                ]
            );
        });

        static::deleted(function (self $beasiswa) {
            if ($beasiswa->isForceDeleting()) {
                Scholarship::where('id', $beasiswa->id)->forceDelete();
            } else {
                Scholarship::where('id', $beasiswa->id)->delete();
            }
        });

        static::restored(function (self $beasiswa) {
            Scholarship::withTrashed()->where('id', $beasiswa->id)->restore();
        });

        static::forceDeleted(function (self $beasiswa) {
            if ($beasiswa->poster) {
                Storage::disk('public')->delete($beasiswa->poster);
            }
        });
    }
}
