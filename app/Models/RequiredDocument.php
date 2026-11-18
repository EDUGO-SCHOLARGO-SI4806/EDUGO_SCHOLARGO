<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredDocument extends Model
{
    use HasFactory;

    protected $table = 'required_documents';

    protected $fillable = [
        'scholarship_id',
        'document_name',
        'description',
        'file_format',
        'is_mandatory',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    /**
     * Relasi ke model Scholarship (Many-to-One)
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}
