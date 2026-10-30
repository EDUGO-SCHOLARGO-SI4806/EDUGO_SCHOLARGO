<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholarship_id',
        'min_gpa',
        'min_semester',
    ];

    protected $casts = [
        'min_gpa' => 'float',
        'min_semester' => 'integer',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}
