<?php

namespace Tests\Browser;

use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;

/**
 * PBI-03 (FR-01) - Sebagai penyelenggara beasiswa, saya ingin melihat status verifikasi akun
 * (menunggu, disetujui, ditolak) beserta catatan penolakan dan dapat mengunggah ulang dokumen
 * yang kurang atau tidak sesuai agar bisa memperbaiki pengajuan.
 */
class Pbi03VerificationStatusTest extends OrganizerBrowserTestCase
{
    private const NOTE = 'Akta pendirian buram dan tidak terbaca. Mohon unggah ulang dengan scan yang jelas.';

    public function test_pending_status_is_shown_without_resubmit_form(): void
    {
        $organizer = $this->createSubmittedOrganizer(OrganizerProfile::STATUS_PENDING);

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.index'))
                ->assertSeeIn('@status-badge', 'Menunggu Verifikasi')
                ->assertMissing('@rejection-note')
                ->assertMissing('@resubmit-form');
        });
    }

    public function test_approved_status_is_shown_without_resubmit_form(): void
    {
        $organizer = $this->createSubmittedOrganizer(OrganizerProfile::STATUS_APPROVED);

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.index'))
                ->assertSeeIn('@status-badge', 'Disetujui')
                ->assertMissing('@rejection-note')
                ->assertMissing('@resubmit-form');
        });
    }

    public function test_rejected_status_shows_rejection_note_and_resubmit_form(): void
    {
        $organizer = $this->createSubmittedOrganizer(OrganizerProfile::STATUS_REJECTED, self::NOTE);

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.index'))
                ->assertSeeIn('@status-badge', 'Ditolak')
                ->assertSeeIn('@rejection-note', self::NOTE)
                ->assertPresent('@resubmit-form');
        });
    }

    public function test_status_follows_the_decision_made_by_super_admin(): void
    {
        $organizer = $this->createSubmittedOrganizer(OrganizerProfile::STATUS_PENDING);
        $reviewer = $this->createOrganizer(['role' => User::ROLE_SUPER_ADMIN]);
        $profile = $organizer->organizerProfile;

        $this->browse(function (Browser $browser) use ($organizer, $profile, $reviewer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.index'))
                ->assertSeeIn('@status-badge', 'Menunggu Verifikasi');

            // Super Admin (FR-02) menolak dengan catatan -> terlihat di sisi penyelenggara
            $profile->reject($reviewer, self::NOTE);

            $browser->refresh()
                ->assertSeeIn('@status-badge', 'Ditolak')
                ->assertSeeIn('@rejection-note', self::NOTE);

            // Super Admin menyetujui -> catatan hilang
            $profile->refresh()->approve($reviewer);

            $browser->refresh()
                ->assertSeeIn('@status-badge', 'Disetujui')
                ->assertMissing('@rejection-note');
        });
    }

    public function test_organizer_can_resubmit_a_document_and_status_returns_to_pending(): void
    {
        $organizer = $this->createSubmittedOrganizer(OrganizerProfile::STATUS_REJECTED, self::NOTE);
        $oldPath = $organizer->organizerProfile->documents()->where('type', 'akta_pendirian')->value('path');

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.index'))
                ->attach('documents[akta_pendirian]', $this->pdfFixture('akta-revisi.pdf'))
                ->click('@submit-resubmit')
                ->assertPathIs('/organizer/documents')
                ->assertSeeIn('@flash-success', 'berhasil diunggah ulang')
                ->assertSeeIn('@status-badge', 'Menunggu Verifikasi')
                ->assertMissing('@rejection-note')
                ->assertMissing('@resubmit-form')
                ->assertSeeIn('@doc-row-akta_pendirian', 'akta-revisi.pdf')
                // dokumen lain tidak berubah
                ->assertSeeIn('@doc-row-nib_npwp', 'nib_npwp-lama.pdf');
        });

        $this->assertDatabaseHas('organizer_profiles', [
            'user_id' => $organizer->id,
            'verification_status' => OrganizerProfile::STATUS_PENDING,
            'verification_note' => null,
        ]);

        // File lama diganti, bukan menumpuk.
        $this->assertFalse(Storage::disk('local')->exists($oldPath), 'File lama seharusnya sudah dihapus');
        $this->assertSame(5, $organizer->organizerProfile->documents()->count());
    }

    public function test_organizer_can_resubmit_several_documents_at_once(): void
    {
        $organizer = $this->createSubmittedOrganizer(OrganizerProfile::STATUS_REJECTED, self::NOTE);

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.index'))
                ->attach('documents[akta_pendirian]', $this->pdfFixture('akta-revisi.pdf'))
                ->attach('documents[surat_kuasa]', $this->jpgFixture('surat-kuasa-revisi.jpg'))
                ->click('@submit-resubmit')
                ->assertSeeIn('@status-badge', 'Menunggu Verifikasi')
                ->assertSeeIn('@doc-row-akta_pendirian', 'akta-revisi.pdf')
                ->assertSeeIn('@doc-row-surat_kuasa', 'surat-kuasa-revisi.jpg');
        });
    }

    public function test_resubmit_without_choosing_any_file_shows_error(): void
    {
        $organizer = $this->createSubmittedOrganizer(OrganizerProfile::STATUS_REJECTED, self::NOTE);

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.index'))
                ->click('@submit-resubmit')
                ->assertPathIs('/organizer/documents')
                ->assertSeeIn('@error-documents', 'Pilih minimal satu dokumen')
                ->assertSeeIn('@status-badge', 'Ditolak');
        });
    }

    public function test_resubmit_with_invalid_format_keeps_status_rejected(): void
    {
        $organizer = $this->createSubmittedOrganizer(OrganizerProfile::STATUS_REJECTED, self::NOTE);

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.index'))
                ->attach('documents[nib_npwp]', $this->textFixture('npwp.txt'))
                ->click('@submit-resubmit')
                ->assertSeeIn('@error-resubmit-nib_npwp', 'harus berformat PDF atau JPG')
                ->assertSeeIn('@status-badge', 'Ditolak')
                ->assertSeeIn('@rejection-note', self::NOTE);
        });

        $this->assertDatabaseHas('organizer_profiles', [
            'user_id' => $organizer->id,
            'verification_status' => OrganizerProfile::STATUS_REJECTED,
        ]);
    }
}