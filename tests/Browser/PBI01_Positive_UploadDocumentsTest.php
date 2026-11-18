<?php

namespace Tests\Browser;

use App\Enums\OrganizerDocumentType;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI01_Positive_UploadDocumentsTest extends DuskTestCase
{
    public function test_organizer_can_upload_all_legal_documents(): void
    {
        $organizer = User::factory()->create([
            'role' => User::ROLE_ORGANIZER,
        ]);

        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('pbi01-');
        mkdir($directory);

        $pdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF";

        $jpg = base64_decode(
            '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA='
        );

        $files = [];

        foreach (OrganizerDocumentType::cases() as $type) {
            $extension = $type === OrganizerDocumentType::IdentitasPerwakilan
                ? 'jpg' : 'pdf';

            $path = $directory . DIRECTORY_SEPARATOR . $type->value . '.' . $extension;

            file_put_contents($path, $extension === 'jpg' ? $jpg : $pdf);

            $files[$type->value] = $path;
        }

        try {
            $this->browse(function (Browser $browser) use ($organizer, $files) {
                $browser->loginAs($organizer)
                    ->visit(route('organizer.documents.create'))
                    ->type('institution_name', 'Yayasan Cendekia Nusantara')
                    ->type('representative_name', 'Dimas Prakoso');

                foreach ($files as $type => $path) {
                    $browser->attach("documents[{$type}]", $path);
                }

                $browser->click('@submit-upload')
                    ->assertPathIs('/organizer/documents')
                    ->assertPresent('@flash-success')
                    ->assertSeeIn('@status-badge', 'Menunggu Verifikasi');
            });

            $this->assertDatabaseHas('organizer_profiles', [
                'user_id' => $organizer->id,
                'verification_status' => 'pending',
            ]);

            $this->assertSame(
                5,
                $organizer->organizerProfile->documents()->count()
            );
        } finally {
            foreach ($files as $path) {
                @unlink($path);
            }

            @rmdir($directory);
        }
    }
}
