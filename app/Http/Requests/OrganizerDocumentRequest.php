<?php

namespace App\Http\Requests;

use App\Enums\OrganizerDocumentType;
use App\Models\OrganizerDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Validator;

/**
 * Aturan validasi file dokumen legalitas yang dipakai bersama oleh
 * pengajuan awal (PBI-01) dan unggah ulang (PBI-03). Isi PBI-02:
 *  - kelengkapan jenis dokumen wajib
 *  - format PDF/JPG
 *  - ukuran maksimal 2 MB
 *  - pesan error yang jelas (Bahasa Indonesia)
 */
abstract class OrganizerDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function fileRules(bool $required): array
    {
        $rules = [];

        foreach (OrganizerDocumentType::cases() as $type) {
            $rules["documents.{$type->value}"] = [
                $required ? 'required' : 'nullable',
                'file',
                'mimes:'.implode(',', OrganizerDocument::ALLOWED_EXTENSIONS),
                'max:'.OrganizerDocument::MAX_SIZE_KB,
            ];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [];

        foreach (OrganizerDocumentType::cases() as $type) {
            $key = "documents.{$type->value}";
            $label = $type->label();

            $messages["{$key}.required"] = "{$label} wajib diunggah.";
            $messages["{$key}.file"] = "{$label} harus berupa file.";
            $messages["{$key}.mimes"] = "{$label} harus berformat PDF atau JPG.";
            $messages["{$key}.max"] = $this->tooLargeMessage($type);
        }

        return $messages;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Jika PHP sendiri menolak file karena terlalu besar (batas upload_max_filesize di php.ini),
            // tampilkan pesan ukuran yang jelas, bukan pesan "wajib diunggah".
            foreach (OrganizerDocumentType::cases() as $type) {
                $key = "documents.{$type->value}";
                $file = $this->file($key);

                if ($file instanceof UploadedFile
                    && in_array($file->getError(), [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
                    $validator->errors()->forget($key);
                    $validator->errors()->add($key, $this->tooLargeMessage($type));
                }
            }

            $this->afterFileChecks($validator);
        });
    }

    /**
     * Pemeriksaan tambahan yang khusus untuk request turunan.
     */
    protected function afterFileChecks(Validator $validator): void
    {
        //
    }

    protected function tooLargeMessage(OrganizerDocumentType $type): string
    {
        $maxMb = OrganizerDocument::MAX_SIZE_KB / 1024;

        return "{$type->label()} terlalu besar. Ukuran file maksimal {$maxMb} MB.";
    }
}