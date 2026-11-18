<?php

namespace Tests\Browser;

use App\Enums\OrganizerDocumentType;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI02_Negative_ValidateDocumentsTest extends DuskTestCase
{
    public function test_submission_is_rejected_when_document_is_missing(): void
    {
        $organizer = User::factory()->create([
            'role' => User::ROLE_ORGANIZER,
        ]);

        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('pbi02-');
        mkdir($directory);

        $pdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF";

        $files = [];

        foreach (OrganizerDocumentType::cases() as $type) {
            if ($type === OrganizerDocumentType::AktaPendirian) {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $type->value . '.pdf';

            file_put_contents($path, $pdf);

            $files[$type->value] = $path;
        }

        try {
            $this->browse(function (Browser $browser) use ($organizer, $files) {
                $browser->loginAs($organizer)
                    ->visit(route('organizer.documents.create'))
                    ->type('institution_name', 'Institusi1')
                    ->type('representative_name', 'Salsabila');

                foreach ($files as $type => $path) {
                    $browser->attach("documents[{$type}]", $path);
                }

                $browser->click('@submit-upload')
                    ->assertPathIs('/organizer/documents/upload')
                    ->assertPresent('@error-summary')
                    ->assertSeeIn(
                        '@error-akta_pendirian',
                        'wajib diunggah'
                    );
            });

            $this->assertDatabaseMissing('organizer_profiles', [
                'user_id' => $organizer->id,
            ]);
        } finally {
            foreach ($files as $path) {
                @unlink($path);
            }

            @rmdir($directory);
        }
    }
}
