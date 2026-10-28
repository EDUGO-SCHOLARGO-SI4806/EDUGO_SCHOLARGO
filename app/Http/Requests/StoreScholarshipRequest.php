<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScholarshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'organizer_name' => 'required|string|max:255',
            'description'    => 'required|string',
            'level'          => 'required|in:D3,D4,S1,S2,S3',
            'category'       => 'nullable|string|max:100',
            'amount'         => 'nullable|integer|min:0',
            'quota'          => 'nullable|integer|min:1',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'source_url'     => 'nullable|url',
            'poster'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'         => 'required|in:draft,published',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'Nama beasiswa wajib diisi.',
            'organizer_name.required'    => 'Penyelenggara wajib diisi.',
            'description.required'       => 'Deskripsi wajib diisi.',
            'level.required'             => 'Jenjang wajib dipilih.',
            'level.in'                   => 'Jenjang harus D3, D4, S1, S2, atau S3.',
            'category.max'               => 'Kategori maksimal 100 karakter.',
            'amount.integer'             => 'Nominal harus berupa angka.',
            'amount.min'                 => 'Nominal tidak boleh negatif.',
            'quota.integer'              => 'Kuota harus berupa angka.',
            'quota.min'                  => 'Kuota minimal 1.',
            'start_date.date'            => 'Tanggal mulai harus berupa tanggal yang valid.',
            'end_date.date'              => 'Deadline harus berupa tanggal yang valid.',
            'end_date.after_or_equal'    => 'Deadline tidak boleh lebih awal dari tanggal mulai.',
            'source_url.url'             => 'Link sumber harus berupa URL yang valid.',
            'poster.image'               => 'Poster harus berupa gambar.',
            'poster.mimes'               => 'Format poster harus JPEG, PNG, JPG, atau WEBP.',
            'poster.max'                 => 'Ukuran poster maksimal 2 MB.',
            'status.required'            => 'Status wajib dipilih.',
            'status.in'                  => 'Status harus draft atau published.',
        ];
    }
}

