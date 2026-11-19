<?php

namespace App\Http\Controllers;

use App\Enums\OrganizerDocumentType;
use App\Http\Requests\ResubmitOrganizerDocumentsRequest;
use App\Http\Requests\StoreOrganizerDocumentsRequest;
use App\Models\OrganizerDocument;
use App\Models\OrganizerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * FR-01 - Pengumpulan Bukti Legalitas Penyelenggara (Sprint 1, Salsabila)
 *
 *  PBI-01  create() + store()      : unggah dokumen legalitas saat pembuatan akun
 *  PBI-02  Form Request            : validasi kelengkapan, format, dan ukuran file
 *  PBI-03  index() + resubmit()    : (ditambahkan di PBI-03)
 */
class OrganizerDocumentController extends Controller
{
    /** Disk privat (storage/app/private): dokumen legalitas tidak boleh publik. */
    private const DISK = 'local';

    /**
     * PBI-03: halaman status verifikasi + daftar dokumen.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $profile = $request->user()->organizerProfile;

        if (! $profile) {
            return redirect()->route('organizer.documents.create');
        }

        return view('organizer.documents.index', [
            'profile' => $profile,
            'documents' => $profile->documents->keyBy(fn (OrganizerDocument $document) => $document->type->value),
            'types' => OrganizerDocumentType::cases(),
        ]);
    }

    /**
     * PBI-01: form unggah dokumen legalitas.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->organizerProfile) {
            return redirect()->route('organizer.documents.index');
        }

        return view('organizer.documents.create', [
            'types' => OrganizerDocumentType::cases(),
        ]);
    }

    /**
     * PBI-01 + PBI-02: simpan data lembaga dan semua dokumen (validasi ada di Form Request).
     */
    public function store(StoreOrganizerDocumentsRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->organizerProfile) {
            return redirect()
                ->route('organizer.documents.index')
                ->with('error', 'Pengajuan verifikasi sudah pernah dikirim.');
        }

        $newPaths = [];

        try {
            DB::transaction(function () use ($request, $user, &$newPaths) {
                $profile = OrganizerProfile::create([
                    'user_id' => $user->id,
                    'institution_name' => $request->validated('institution_name'),
                    'representative_name' => $request->validated('representative_name'),
                    'verification_status' => OrganizerProfile::STATUS_PENDING,
                    'submitted_at' => now(),
                ]);

                foreach (OrganizerDocumentType::cases() as $type) {
                    [$document] = $this->saveDocument($profile, $type, $request->file("documents.{$type->value}"));
                    $newPaths[] = $document->path;
                }
            });
        } catch (Throwable $e) {
            // Gagal di tengah jalan: buang file yang sudah sempat tersimpan agar tidak jadi sampah.
            Storage::disk(self::DISK)->delete($newPaths);

            throw $e;
        }

        return redirect()
            ->route('organizer.documents.index')
            ->with('success', 'Dokumen berhasil dikirim. Pengajuan Anda sedang menunggu verifikasi Admin.');
    }

        /**
     * PBI-03: unggah ulang dokumen yang kurang / tidak sesuai setelah pengajuan ditolak.
     */
    public function resubmit(ResubmitOrganizerDocumentsRequest $request): RedirectResponse
    {
        $profile = $request->user()->organizerProfile;

        if (! $profile) {
            return redirect()->route('organizer.documents.create');
        }

        if (! $profile->isRejected()) {
            return redirect()
                ->route('organizer.documents.index')
                ->with('error', 'Dokumen hanya dapat diunggah ulang ketika pengajuan berstatus ditolak.');
        }

        $newPaths = [];
        $oldPaths = [];

        try {
            DB::transaction(function () use ($request, $profile, &$newPaths, &$oldPaths) {
                foreach (OrganizerDocumentType::cases() as $type) {
                    $file = $request->file("documents.{$type->value}");

                    if (! $file) {
                        continue;
                    }

                    [$document, $oldPath] = $this->saveDocument($profile, $type, $file);

                    $newPaths[] = $document->path;

                    if ($oldPath) {
                        $oldPaths[] = $oldPath;
                    }
                }

                $profile->resubmit();
            });
        } catch (Throwable $e) {
            Storage::disk(self::DISK)->delete($newPaths);

            throw $e;
        }

        // File lama baru dihapus setelah database berhasil diperbarui.
        Storage::disk(self::DISK)->delete($oldPaths);

        return redirect()
            ->route('organizer.documents.index')
            ->with('success', 'Dokumen berhasil diunggah ulang. Pengajuan Anda kembali menunggu verifikasi.');
    }
    
    /**
     * Unduh dokumen - hanya boleh oleh pemiliknya.
     */
    public function download(Request $request, OrganizerDocument $document): Response
    {
        abort_unless($document->profile->user_id === $request->user()->id, 403);

        $disk = Storage::disk(self::DISK);

        abort_unless($disk->exists($document->path), 404, 'File dokumen tidak ditemukan.');

        return $disk->download($document->path, $document->original_name);
    }

    /**
     * Simpan file ke disk privat lalu catat/ganti barisnya di database.
     *
     * @return array{0: OrganizerDocument, 1: string|null} dokumen + path file lama (jika mengganti)
     */
    private function saveDocument(OrganizerProfile $profile, OrganizerDocumentType $type, UploadedFile $file): array
    {
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType() ?? 'application/octet-stream';
        $size = $file->getSize();

        $path = $file->store(config('scholargo.documents_directory'), self::DISK);

        $oldPath = $profile->documents()->where('type', $type->value)->value('path');

        $document = $profile->documents()->updateOrCreate(
            ['type' => $type->value],
            [
                'original_name' => $originalName,
                'path' => $path,
                'mime_type' => $mimeType,
                'size_bytes' => $size,
            ],
        );

        return [$document, $oldPath];
    }
}
