<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Scholarship extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'organizer_name', 'description', 'level',
        'category', 'amount', 'quota', 'start_date', 'end_date',
        'source_url', 'poster', 'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'amount'     => 'integer',
            'quota'      => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::forceDeleted(function (self $scholarship) {
            if ($scholarship->poster) {
                Storage::disk('public')->delete($scholarship->poster);
            }
        });
    }
}
