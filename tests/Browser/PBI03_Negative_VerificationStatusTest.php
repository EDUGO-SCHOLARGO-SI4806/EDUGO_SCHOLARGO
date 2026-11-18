<?php

namespace Tests\Browser;

use App\Enums\OrganizerDocumentType;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI03_Negative_VerificationStatusTest extends DuskTestCase
{
    public function test_resubmit_without_document_is_rejected(): void
    {
        $organizer = User::factory()->create([
            'role' => User::ROLE_ORGANIZER,
        ]);

        $profile = OrganizerProfile::create([
            'user_id' => $organizer->id,
            'institution_name' => 'Institusi1',
            'representative_name' => 'Salsabila',
            'verification_status' => OrganizerProfile::STATUS_REJECTED,
            'verification_note' => 'Akta pendirian buram dan tidak terbaca.',
            'submitted_at' => now(),
        ]);

        $directoryStorage = config('scholargo.documents_directory');

        foreach (OrganizerDocumentType::cases() as $type) {
            $path = $directoryStorage . '/' . uniqid($type->value . '-') . '.pdf';

            $content = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF";

            Storage::disk('local')->put($path, $content);

            $profile->documents()->create([
                'type' => $type->value,
                'original_name' => $type->value . '-lama.pdf',
                'path' => $path,
                'mime_type' => 'application/pdf',
                'size_bytes' => strlen($content),
            ]);
        }

        try {
            $this->browse(function (Browser $browser) use ($organizer) {
                $browser->loginAs($organizer)
                    ->visit(route('organizer.documents.index'))
                    ->assertSeeIn('@status-badge', 'Ditolak')
                    ->click('@submit-resubmit')
                    ->assertPathIs('/organizer/documents')
                    ->assertSeeIn(
                        '@error-documents',
                        'Pilih minimal satu dokumen untuk diunggah ulang.'
                    )
                    ->assertSeeIn('@status-badge', 'Ditolak')
                    ->assertPresent('@rejection-note');
            });

            $this->assertDatabaseHas('organizer_profiles', [
                'user_id' => $organizer->id,
                'verification_status' => OrganizerProfile::STATUS_REJECTED,
            ]);
        } finally {
            Storage::disk('local')->deleteDirectory($directoryStorage);
        }
    }
}
