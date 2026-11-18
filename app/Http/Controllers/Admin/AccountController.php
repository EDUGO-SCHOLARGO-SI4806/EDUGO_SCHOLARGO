<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrganizerDocumentType;
use App\Http\Controllers\Controller;
use App\Models\OrganizerDocument;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /** Disk privat tempat Salsabila menyimpan dokumen legalitas (storage/app/private). */
    private const DOCUMENT_DISK = 'local';

    /**
     * PBI-04 dan PBI-05: daftar akun, dengan pencarian dan filter.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(array_keys(User::ROLE_LABELS))],
            'status' => ['nullable', Rule::in(array_keys(User::STATUS_LABELS))],
        ]);

        $accounts = User::query()
            ->with('organizerProfile')
            ->when($filters['q'] ?? null, function ($query, $term) {
                $like = '%'.addcslashes($term, '%_\\').'%';

                $query->where(fn ($q) => $q->where('name', 'like', $like)->orWhere('email', 'like', $like));
            })
            ->when($filters['role'] ?? null, fn ($query, $role) => $query->where('role', $role))
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->whereHas('organizerProfile', fn ($profile) => $profile->where('verification_status', $status));
            })
            ->orderByDesc('created_at')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.accounts.index', [
            'accounts' => $accounts,
            'filters' => $filters,
            'hasFilter' => collect($filters)->filter()->isNotEmpty(),
            'pendingCount' => OrganizerProfile::where('verification_status', OrganizerProfile::STATUS_PENDING)->count(),
        ]);
    }

    /**
     * PBI-04 dan PBI-06: detail akun (penyelenggara juga menampilkan dokumen legalitas).
     */
    public function show(User $user): View
    {
        $user->load('organizerProfile.documents', 'organizerProfile.reviewer');

        return view('admin.accounts.show', [
            'account' => $user,
            'profile' => $user->organizerProfile,
            'types' => OrganizerDocumentType::cases(),
        ]);
    }

    /**
     * PBI-06: lihat satu dokumen legalitas (disimpan privat, jadi dialirkan lewat controller).
     */
    public function document(OrganizerDocument $document): Response
    {
        $disk = Storage::disk(self::DOCUMENT_DISK);

        abort_unless($disk->exists($document->path), 404, 'File dokumen tidak ditemukan.');

        return $disk->response($document->path, $document->original_name);
    }

    /**
     * PBI-06: setujui akun penyelenggara.
     */
    public function approve(Request $request, User $user): RedirectResponse
    {
        $profile = $this->pendingProfile($user);

        if (! $profile) {
            return back()->with('error', 'Hanya pengajuan berstatus menunggu yang dapat diproses.');
        }

        if ($profile->documents()->count() < count(OrganizerDocumentType::cases())) {
            return back()->with('error', 'Akun belum bisa disetujui karena dokumen legalitas belum lengkap.');
        }

        $profile->approve($request->user());

        return redirect()
            ->route('admin.accounts.show', $user)
            ->with('status', "Akun {$user->name} berhasil disetujui.");
    }

    /**
     * PBI-06: tolak akun penyelenggara (catatan wajib diisi).
     */
    public function reject(Request $request, User $user): RedirectResponse
    {
        $profile = $this->pendingProfile($user);

        if (! $profile) {
            return back()->with('error', 'Hanya pengajuan berstatus menunggu yang dapat diproses.');
        }

        $data = $request->validate([
            'note' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'note.required' => 'Catatan penolakan wajib diisi.',
            'note.min' => 'Catatan penolakan minimal 5 karakter.',
            'note.max' => 'Catatan penolakan maksimal 500 karakter.',
        ]);

        $profile->reject($request->user(), $data['note']);

        return redirect()
            ->route('admin.accounts.show', $user)
            ->with('status', "Akun {$user->name} ditolak.");
    }

    /**
     * Profil penyelenggara yang statusnya masih menunggu (null kalau sudah diputuskan).
     * 404 kalau akunnya bukan penyelenggara atau belum pernah mengirim dokumen.
     */
    private function pendingProfile(User $user): ?OrganizerProfile
    {
        abort_unless($user->isOrganizer() && $user->organizerProfile, 404);

        return $user->organizerProfile->isPending() ? $user->organizerProfile : null;
    }
}