<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organizer',
        'description',
    ];

    public function requirement()
    {
        return $this->hasOne(ScholarshipRequirement::class);
    }
}
