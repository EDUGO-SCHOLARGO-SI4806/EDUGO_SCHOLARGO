<?php

namespace App\Http\Requests;

/**
 * Pengajuan verifikasi awal: data lembaga + SEMUA dokumen wajib (PBI-01 & PBI-02).
 */
class StoreOrganizerDocumentsRequest extends OrganizerDocumentRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge([
            'institution_name' => ['required', 'string', 'max:150'],
            'representative_name' => ['required', 'string', 'max:100'],
        ], $this->fileRules(required: true));
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'institution_name.required' => 'Nama lembaga wajib diisi.',
            'institution_name.max' => 'Nama lembaga maksimal 150 karakter.',
            'representative_name.required' => 'Nama perwakilan wajib diisi.',
            'representative_name.max' => 'Nama perwakilan maksimal 100 karakter.',
        ]);
    }
}