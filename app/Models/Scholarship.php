<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $table = 'scholarships';

    protected $fillable = [
        'name',
        'organizer',
        'description',
        'level',
        'category',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // Accessor: organizer_name adalah alias organizer (kompatibel dengan view Fira)
    public function getOrganizerNameAttribute(): ?string
    {
        return $this->organizer;
    }

    public function requirement()
    {
        return $this->hasOne(ScholarshipRequirement::class, 'scholarship_id');
    }

    /**
     * Relasi daftar dokumen wajib per beasiswa (PBI-14 / ED-14)
     */
    public function requiredDocuments()
    {
        return $this->hasMany(RequiredDocument::class, 'scholarship_id');
    }

    /**
     * Relasi periode pendaftaran per beasiswa (PBI-15)
     */
    public function registrationPeriod()
    {
        return $this->hasOne(RegistrationPeriod::class, 'scholarship_id');
    }
}
