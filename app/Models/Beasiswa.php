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

    // Accessor: Hitung sisa hari sampai batas pendaftaran (milik Fira)
    public function getSisaHariAttribute(): int
    {
        if (! $this->tanggal_tutup) {
            return 0;
        }

        return max(0, (int) now()->startOfDay()->diffInDays($this->tanggal_tutup->copy()->startOfDay(), false));
    }

    // Scope: Hanya beasiswa aktif / masih dibuka (milik Fira)
    public function scopeAktif(Builder $q): Builder
    {
        return $q->where('status', 'published')
                 ->where(function ($query) {
                     $query->whereNull('tanggal_tutup')
                           ->orWhereDate('tanggal_tutup', '>=', today());
                 });
    }

    // Scope: Filter pencarian (milik Fira)
    public function scopeFilter(Builder $q, array $f): Builder
    {
        return $q
            ->when($f['q'] ?? null, fn ($q, $v) => $q->where(function ($s) use ($v) {
                $s->where('nama', 'like', "%{$v}%")
                  ->orWhere('penyelenggara', 'like', "%{$v}%");
            }))
            ->when($f['batas_waktu'] ?? null, fn ($q, $v) =>
                $q->whereDate('tanggal_tutup', '<=', today()->addDays((int) $v)))
            ->when($f['jenjang'] ?? null, fn ($q, $v) => $q->where('jenjang', 'like', "%{$v}%"))
            ->when($f['pendanaan'] ?? null, fn ($q, $v) => $q->where('jenis_pendanaan', $v));
    }

    // Accessor kompatibilitas agar kode/view lama tidak error
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

    // Otomatis hapus file poster saat force delete
    protected static function booted(): void
    {
        static::forceDeleted(function (self $beasiswa) {
            if ($beasiswa->poster) {
                Storage::disk('public')->delete($beasiswa->poster);
            }
        });
    }
}
