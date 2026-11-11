<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'penyelenggara', 'jenjang', 'lokasi',
        'jenis_pendanaan', 'deskripsi', 'tanggal_tutup',
    ];

    protected $casts = [
        'tanggal_tutup' => 'date',
    ];

    //sisa hari smpe batas pndftaran
    public function getSisaHariAttribute(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->tanggal_tutup->copy()->startOfDay(), false));
    }

    // beasiswa yg dibuka
    public function scopeAktif(Builder $q): Builder
    {
        return $q->whereDate('tanggal_tutup', '>=', today());
    }

    public function scopeFilter(Builder $q, array $f): Builder
    {
        return $q
            ->when($f['q'] ?? null, function ($query, $keyword) {
                foreach (preg_split('/\s+/', trim($keyword)) as $kata) {
                    $kata = addcslashes($kata, '%_\\');

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
            ->when($f['pendanaan'] ?? null, fn ($q, $v) => $q->where('jenis_pendanaan', $v));
    }
}