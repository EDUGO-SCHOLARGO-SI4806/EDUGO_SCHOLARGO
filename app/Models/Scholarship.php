<?php

namespace App\Models;

/**
 * Class Scholarship
 * Alias/Wrapper ke Model Beasiswa demi backward compatibility,
 * sekaligus membawa relasi requirements, documents, dan period untuk PBI-13, 14, 15.
 */
class Scholarship extends Beasiswa
{
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
