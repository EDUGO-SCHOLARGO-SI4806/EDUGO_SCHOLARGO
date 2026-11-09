<?php

namespace App\Http\Controllers;

use App\Models\RequiredDocument;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use Illuminate\Http\Request;

class ScholarshipRequirementController extends Controller
{
    /**
     * Menampilkan daftar beasiswa, kriteria kualifikasi (PBI-13), dan daftar dokumen wajib (PBI-14)
     */
    public function index()
    {
        $scholarships = Scholarship::with(['requirement', 'requiredDocuments'])->get();

        return view('requirements.index', compact('scholarships'));
    }

    /**
     * Menampilkan form untuk mengatur kriteria kualifikasi beasiswa (PBI-13)
     */
    public function edit($id)
    {
        $scholarship = Scholarship::with('requirement')->findOrFail($id);
        
        $requirement = $scholarship->requirement ?? new ScholarshipRequirement([
            'scholarship_id' => $scholarship->id,
            'min_gpa' => 3.00,
            'min_semester' => 1,
        ]);

        return view('requirements.edit', compact('scholarship', 'requirement'));
    }

    /**
     * Menyimpan pembaruan kriteria kualifikasi beasiswa (PBI-13)
     */
    public function update(Request $request, $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        $validated = $request->validate([
            'min_gpa' => 'required|numeric|between:0.00,4.00',
            'min_semester' => 'required|integer|between:1,14',
        ], [
            'min_gpa.required' => 'IPK minimal wajib diisi.',
            'min_gpa.numeric' => 'IPK minimal harus berupa angka valid.',
            'min_gpa.between' => 'IPK minimal harus berada di antara 0.00 dan 4.00.',
            'min_semester.required' => 'Semester minimal wajib diisi.',
            'min_semester.integer' => 'Semester minimal harus berupa bilangan bulat.',
            'min_semester.between' => 'Semester minimal harus berada di antara semester 1 dan 14.',
        ]);

        $scholarship->requirement()->updateOrCreate(
            ['scholarship_id' => $scholarship->id],
            [
                'min_gpa' => $validated['min_gpa'],
                'min_semester' => $validated['min_semester'],
            ]
        );

        return redirect()
            ->route('requirements.index')
            ->with('success', "Kriteria kualifikasi untuk beasiswa '{$scholarship->name}' berhasil disimpan.");
    }

    /**
     * Menampilkan halaman kelola daftar dokumen wajib per beasiswa (PBI-14 / ED-14)
     */
    public function documents($id)
    {
        $scholarship = Scholarship::with(['requirement', 'requiredDocuments'])->findOrFail($id);

        // Daftar rekomendasi dokumen umum beasiswa untuk memudahkan admin memilih
        $presetDocuments = [
            'Transkrip Nilai Akademik' => 'Transkrip nilai akademik semester terakhir yang disahkan oleh pihak fakultas/kampus.',
            'Kartu Tanda Penduduk (KTP)' => 'Salinan identitas resmi KTP elektronik yang masih berlaku dan terbaca jelas.',
            'Kartu Tanda Mahasiswa (KTM)' => 'Kartu identitas mahasiswa aktif dari perguruan tinggi asal.',
            'Surat Rekomendasi Dosen / Kampus' => 'Surat rekomendasi resmi dari dosen pembimbing akademik atau pimpinan fakultas.',
            'Surat Pernyataan Tidak Menerima Beasiswa Lain' => 'Surat pernyataan bermeterai bahwa pendaftar tidak sedang menerima beasiswa dari instansi lain.',
            'Surat Keterangan Tidak Mampu (SKTM) / Slip Gaji Orang Tua' => 'Dokumen keterangan kondisi finansial keluarga dari kelurahan atau instansi terkait.',
            'Motivation Letter / Esai Beasiswa' => 'Esai motivasi diri dan rencana kontribusi studi (maksimal 2 halaman).',
            'Sertifikat Prestasi / Organisasi' => 'Piagam penghargaan akademik/non-akademik atau bukti keaktifan organisasi mahasiswa.',
        ];

        return view('requirements.documents', compact('scholarship', 'presetDocuments'));
    }

    /**
     * Menambahkan dokumen wajib baru pada beasiswa tertentu (PBI-14 / ED-14)
     */
    public function storeDocument(Request $request, $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        $validated = $request->validate([
            'document_name' => 'required|string|max:150',
            'description'   => 'nullable|string|max:255',
            'file_format'   => 'required|string|in:PDF,JPG,PDF, JPG',
            'is_mandatory'  => 'required|boolean',
        ], [
            'document_name.required' => 'Nama dokumen wajib diisi.',
            'document_name.max'      => 'Nama dokumen maksimal 150 karakter.',
            'description.max'        => 'Keterangan dokumen maksimal 255 karakter.',
            'file_format.required'   => 'Format berkas wajib dipilih.',
            'file_format.in'         => 'Format berkas harus berupa PDF, JPG, atau keduanya.',
            'is_mandatory.required'  => 'Status sifat dokumen (Wajib/Opsional) harus dipilih.',
        ]);

        // Cek apakah dokumen dengan nama yang sama sudah terdaftar di beasiswa ini
        $exists = $scholarship->requiredDocuments()
            ->whereRaw('LOWER(document_name) = ?', [strtolower(trim($validated['document_name']))])
            ->exists();

        if ($exists) {
            return redirect()
                ->route('requirements.documents', $scholarship->id)
                ->withErrors(['document_name' => "Dokumen '{$validated['document_name']}' sudah ada dalam daftar persyaratan beasiswa ini."])
                ->withInput();
        }

        $scholarship->requiredDocuments()->create([
            'document_name' => trim($validated['document_name']),
            'description'   => $validated['description'] ? trim($validated['description']) : null,
            'file_format'   => $validated['file_format'],
            'is_mandatory'  => (bool) $validated['is_mandatory'],
        ]);

        return redirect()
            ->route('requirements.documents', $scholarship->id)
            ->with('success', "Dokumen '{$validated['document_name']}' berhasil ditambahkan ke persyaratan beasiswa '{$scholarship->name}'.");
    }

    /**
     * Mengubah status dokumen antara Wajib dan Opsional (PBI-14 / ED-14)
     */
    public function toggleDocumentMandatory($id, $docId)
    {
        $scholarship = Scholarship::findOrFail($id);
        $document = RequiredDocument::where('scholarship_id', $scholarship->id)->findOrFail($docId);

        $document->update([
            'is_mandatory' => !$document->is_mandatory,
        ]);

        $statusLabel = $document->is_mandatory ? 'Wajib' : 'Opsional';

        return redirect()
            ->route('requirements.documents', $scholarship->id)
            ->with('success', "Status dokumen '{$document->document_name}' berhasil diubah menjadi {$statusLabel}.");
    }

    /**
     * Menghapus dokumen dari daftar dokumen wajib beasiswa (PBI-14 / ED-14)
     */
    public function destroyDocument($id, $docId)
    {
        $scholarship = Scholarship::findOrFail($id);
        $document = RequiredDocument::where('scholarship_id', $scholarship->id)->findOrFail($docId);
        $docName = $document->document_name;

        $document->delete();

        return redirect()
            ->route('requirements.documents', $scholarship->id)
            ->with('success', "Dokumen '{$docName}' berhasil dihapus dari daftar persyaratan.");
    }

    /**
     * Menampilkan tampilan publik mahasiswa (Mockup Gambar 26) dengan Tab Persyaratan aktif
     */
    public function show($id)
    {
        $scholarship = Scholarship::with(['requirement', 'requiredDocuments'])->findOrFail($id);

        return view('requirements.show', compact('scholarship'));
    }
}
