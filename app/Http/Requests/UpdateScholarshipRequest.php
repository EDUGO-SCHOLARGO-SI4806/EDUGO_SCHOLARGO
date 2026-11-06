<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScholarshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        if ($this->filled('name') && ! $this->filled('nama')) {
            $merge['nama'] = $this->name;
        }
        if ($this->filled('organizer_name') && ! $this->filled('penyelenggara')) {
            $merge['penyelenggara'] = $this->organizer_name;
        }
        if ($this->filled('description') && ! $this->filled('deskripsi')) {
            $merge['deskripsi'] = $this->description;
        }
        if ($this->filled('level') && ! $this->filled('jenjang')) {
            $merge['jenjang'] = $this->level;
        }
        if ($this->filled('end_date') && ! $this->filled('tanggal_tutup')) {
            $merge['tanggal_tutup'] = $this->end_date;
        }
        if ($this->filled('start_date') && ! $this->filled('tanggal_buka')) {
            $merge['tanggal_buka'] = $this->start_date;
        }
        if ($this->filled('amount') && ! $this->filled('nominal')) {
            $merge['nominal'] = $this->amount;
        }
        if ($this->filled('quota') && ! $this->filled('kuota')) {
            $merge['kuota'] = $this->quota;
        }
        if ($this->filled('category') && ! $this->filled('kategori')) {
            $merge['kategori'] = $this->category;
        }
        if ($this->filled('source_url') && ! $this->filled('link_sumber')) {
            $merge['link_sumber'] = $this->source_url;
        }

        if (! empty($merge)) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'nama'            => 'required|string|max:255',
            'penyelenggara'   => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'jenjang'         => 'required|in:D3,D4,S1,S2,S3',
            'kategori'        => 'nullable|string|max:100',
            'lokasi'          => 'nullable|string|max:255',
            'jenis_pendanaan' => 'nullable|string|max:100',
            'nominal'         => 'nullable|integer|min:0',
            'kuota'           => 'nullable|integer|min:1',
            'tanggal_buka'    => 'nullable|date',
            'tanggal_tutup'   => 'nullable|date|after_or_equal:tanggal_buka',
            'link_sumber'     => 'nullable|url',
            'poster'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'hapus_poster'    => 'nullable|boolean',
            'status'          => 'required|in:draft,published',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'                 => 'Nama beasiswa wajib diisi.',
            'penyelenggara.required'        => 'Penyelenggara wajib diisi.',
            'deskripsi.required'            => 'Deskripsi wajib diisi.',
            'jenjang.required'              => 'Jenjang wajib dipilih.',
            'jenjang.in'                    => 'Jenjang harus D3, D4, S1, S2, atau S3.',
            'kategori.max'                  => 'Kategori maksimal 100 karakter.',
            'nominal.integer'               => 'Nominal harus berupa angka.',
            'nominal.min'                   => 'Nominal tidak boleh negatif.',
            'kuota.integer'                 => 'Kuota harus berupa angka.',
            'kuota.min'                     => 'Kuota minimal 1.',
            'tanggal_buka.date'             => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_tutup.date'            => 'Deadline harus berupa tanggal yang valid.',
            'tanggal_tutup.after_or_equal'  => 'Deadline tidak boleh lebih awal dari tanggal mulai.',
            'link_sumber.url'               => 'Link sumber harus berupa URL yang valid.',
            'poster.image'                  => 'Poster harus berupa gambar.',
            'poster.mimes'                  => 'Format poster harus JPEG, PNG, JPG, atau WEBP.',
            'poster.max'                    => 'Ukuran poster maksimal 2 MB.',
            'status.required'               => 'Status wajib dipilih.',
            'status.in'                     => 'Status harus draft atau published.',
        ];
    }
}
