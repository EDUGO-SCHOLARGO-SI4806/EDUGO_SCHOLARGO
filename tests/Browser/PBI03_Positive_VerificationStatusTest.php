<?php

namespace Tests\Browser;

use App\Enums\OrganizerDocumentType;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI03_Positive_VerificationStatusTest extends DuskTestCase
{
    public function test_rejected_organizer_can_resubmit_document(): void
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

        $oldPath = $profile->documents()
            ->where('type', OrganizerDocumentType::AktaPendirian->value)
            ->value('path');

        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('pbi03-');
        mkdir($directory);

        $newFile = $directory . DIRECTORY_SEPARATOR . 'akta-revisi.pdf';

        file_put_contents(
            $newFile,
            "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF"
        );

        try {
            $this->browse(function (Browser $browser) use ($organizer, $newFile) {
                $browser->loginAs($organizer)
                    ->visit(route('organizer.documents.index'))
                    ->assertSeeIn('@status-badge', 'Ditolak')
                    ->attach('documents[akta_pendirian]', $newFile)
                    ->click('@submit-resubmit')
                    ->assertPathIs('/organizer/documents')
                    ->assertPresent('@flash-success')
                    ->assertSeeIn('@status-badge', 'Menunggu Verifikasi')
                    ->assertMissing('@rejection-note')
                    ->assertMissing('@resubmit-form')
                    ->assertSeeIn('@doc-row-akta_pendirian', 'akta-revisi.pdf');
            });

            $this->assertDatabaseHas('organizer_profiles', [
                'user_id' => $organizer->id,
                'verification_status' => OrganizerProfile::STATUS_PENDING,
                'verification_note' => null,
            ]);

            $this->assertSame(
                5,
                $profile->documents()->count()
            );

            $this->assertFalse(
                Storage::disk('local')->exists($oldPath)
            );
        } finally {
            @unlink($newFile);
            @rmdir($directory);

            Storage::disk('local')->deleteDirectory($directoryStorage);
        }
    }
}
