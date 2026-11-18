<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;

/**
 * Unggah ulang dokumen setelah pengajuan ditolak (PBI-03).
 * Penyelenggara boleh memilih sebagian dokumen saja (yang kurang / tidak sesuai).
 */
class ResubmitOrganizerDocumentsRequest extends OrganizerDocumentRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->fileRules(required: false);
    }

    protected function afterFileChecks(Validator $validator): void
    {
        $chosen = array_filter((array) $this->file('documents'));

        if ($chosen === []) {
            $validator->errors()->add('documents', 'Pilih minimal satu dokumen untuk diunggah ulang.');
        }
    }
}