<?php

namespace App\Services;

use App\Models\Scholarship;
use App\Models\StudentProfile;

/**
 * PBI-22: cek kesesuaian profil mahasiswa (IPK, semester) dengan syarat beasiswa.
 * Hanya menilai syarat kualifikasi. Status periode pendaftaran dicek terpisah (PBI-23).
 */
class EligibilityService
{
    /**
     * @return array{status: string, checks: array<int, array{label: string, required: mixed, actual: mixed, passed: bool}>}
     *   status: no_requirement | incomplete_profile | eligible | not_eligible
     */
    public function check(?StudentProfile $profile, Scholarship $scholarship): array
    {
        $requirement = $scholarship->requirement;

        if (! $requirement) {
            return ['status' => 'no_requirement', 'checks' => []];
        }

        if (! $profile || $profile->ipk === null || $profile->semester === null) {
            return ['status' => 'incomplete_profile', 'checks' => []];
        }

        $checks = [
            [
                'label'    => 'IPK minimum',
                'required' => number_format((float) $requirement->min_gpa, 2),
                'actual'   => number_format((float) $profile->ipk, 2),
                'passed'   => round((float) $profile->ipk, 2) >= round((float) $requirement->min_gpa, 2),
            ],
            [
                'label'    => 'Semester minimum',
                'required' => (int) $requirement->min_semester,
                'actual'   => (int) $profile->semester,
                'passed'   => (int) $profile->semester >= (int) $requirement->min_semester,
            ],
        ];

        $allPassed = collect($checks)->every(fn (array $c) => $c['passed']);

        return [
            'status' => $allPassed ? 'eligible' : 'not_eligible',
            'checks' => $checks,
        ];
    }
}