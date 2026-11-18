<?php

namespace App\Http\Controllers;

use App\Models\ProfileDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileDocumentController extends Controller
{
    /**
     * PBI-09: Menampilkan halaman upload dokumen profil
     */
    public function index()
    {
        $user = $this->currentUser();
        $documents = $user->profileDocuments()->latest()->get();
        $documentTypes = ProfileDocument::DOCUMENT_TYPES;

        return view('profile.documents', compact('documents', 'documentTypes'));
    }

    /**
     * PBI-09: Upload dokumen profil
     * Validasi: PDF/JPG/JPEG/PNG, max 2MB
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:ktm,transkrip,foto,ktp,lainnya',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'document_type.required' => 'Pilih jenis dokumen.',
            'document_type.in' => 'Jenis dokumen tidak valid.',
            'document.required' => 'File dokumen wajib diunggah.',
            'document.file' => 'Upload harus berupa file.',
            'document.mimes' => 'Format file harus PDF, JPG, JPEG, atau PNG.',
            'document.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        $user = $this->currentUser();
        $file = $request->file('document');

        // Cek apakah sudah ada dokumen dengan tipe yang sama
        $existing = $user->profileDocuments()
            ->where('document_type', $request->document_type)
            ->first();

        if ($existing) {
            // Hapus file lama
            Storage::disk('public')->delete($existing->file_path);
            $existing->delete();
        }

        // Simpan file baru
        $fileName = $user->id . '_' . $request->document_type . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('profile_documents', $fileName, 'public');

        ProfileDocument::create([
            'user_id' => $user->id,
            'document_type' => $request->document_type,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('profile.documents')
            ->with('success', 'Dokumen berhasil diunggah!');
    }

    /**
     * PBI-09: Download dokumen
     */
    public function download(ProfileDocument $document)
    {
        $user = $this->currentUser();

        // Pastikan dokumen milik user yang sedang dipakai
        if ($document->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        abort_unless(Storage::disk('public')->exists($document->file_path), 404, 'File dokumen tidak ditemukan.');

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_name
        );
    }

    /**
     * PBI-09: Hapus dokumen
     */
    public function destroy(ProfileDocument $document)
    {
        $user = $this->currentUser();

        if ($document->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Hapus file dari storage
        Storage::disk('public')->delete($document->file_path);

        // Hapus record dari database
        $document->delete();

        return redirect()->route('profile.documents')
            ->with('success', 'Dokumen berhasil dihapus!');
    }

    /**
     * SEMENTARA: fitur login belum ada, jadi yang dipakai adalah user pertama di database
     * (sama seperti ProfileController). TODO: ganti dengan Auth::user() dan pasang
     * middleware 'auth' di route setelah fitur login tersedia.
     */
    private function currentUser(): User
    {
        $user = User::query()->orderBy('id')->first();

        abort_if($user === null, 404, 'Belum ada data. Jalankan: php artisan migrate --seed');

        return $user;
    }
}
