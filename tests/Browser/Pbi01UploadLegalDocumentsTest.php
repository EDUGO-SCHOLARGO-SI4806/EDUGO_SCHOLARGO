<?php

namespace Tests\Browser;

use App\Enums\OrganizerDocumentType;
use App\Models\OrganizerDocument;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;

/**
 * PBI-01 (FR-01) - Sebagai penyelenggara beasiswa, saya ingin mengunggah dokumen legalitas
 * lembaga (akta pendirian, SK pengesahan badan hukum, NIB/NPWP, surat kuasa/surat tugas,
 * identitas perwakilan) agar akun saya dapat diverifikasi.
 */
class Pbi01UploadLegalDocumentsTest extends OrganizerBrowserTestCase
{
    public function test_upload_page_shows_all_required_document_fields(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'))
                ->assertPathIs('/organizer/documents/upload')
                ->assertSee('Verifikasi Akun Penyelenggara')
                ->assertPresent('input[name="institution_name"]')
                ->assertPresent('input[name="representative_name"]');

            foreach (OrganizerDocumentType::cases() as $type) {
                $browser->assertSee($type->label())
                    ->assertPresent('input[name="documents['.$type->value.']"]');
            }
        });
    }

    public function test_organizer_can_upload_all_legal_documents(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'));

            $this->fillInstitutionData($browser);
            $this->attachValidDocuments($browser);

            $browser->click('@submit-upload')
                ->assertPathIs('/organizer/documents')
                ->assertPresent('@flash-success')
                ->assertSeeIn('@flash-success', 'Dokumen berhasil dikirim')
                ->assertSeeIn('@status-badge', 'Menunggu Verifikasi')
                ->assertSee('Yayasan Cendekia Nusantara');

            foreach (OrganizerDocumentType::cases() as $type) {
                $browser->assertPresent('@doc-row-'.$type->value)
                    ->assertPresent('@download-'.$type->value);
            }
        });

        $this->assertDatabaseHas('organizer_profiles', [
            'user_id' => $organizer->id,
            'institution_name' => 'Yayasan Cendekia Nusantara',
            'representative_name' => 'Dimas Prakoso',
            'verification_status' => OrganizerProfile::STATUS_PENDING,
        ]);
        $this->assertDatabaseCount('organizer_documents', count(OrganizerDocumentType::cases()));

        // Setiap file benar-benar tersimpan di disk privat.
        foreach (OrganizerDocument::all() as $document) {
            $this->assertTrue(Storage::disk('local')->exists($document->path), "File {$document->path} tidak ditemukan");
        }
    }

    public function test_uploaded_file_names_are_listed_on_status_page(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'));

            $this->fillInstitutionData($browser);
            $this->attachValidDocuments($browser);

            $browser->click('@submit-upload')
                ->assertSeeIn('@doc-row-akta_pendirian', 'akta_pendirian.pdf')
                ->assertSeeIn('@doc-row-identitas_perwakilan', 'identitas-perwakilan.jpg');
        });
    }

    public function test_organizer_cannot_download_another_organizers_document(): void
    {
        $owner = $this->createSubmittedOrganizer();
        $intruder = $this->createOrganizer();
        $document = $owner->organizerProfile->documents()->first();

        $this->browse(function (Browser $browser) use ($intruder, $document) {
            $browser->loginAs($intruder)
                ->visit(route('organizer.documents.download', $document))
                ->assertSee('403');
        });
    }

    public function test_organizer_who_already_submitted_is_sent_to_status_page(): void
    {
        $organizer = $this->createSubmittedOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'))
                ->assertPathIs('/organizer/documents')
                ->assertPresent('@status-badge');
        });
    }

    public function test_non_organizer_cannot_open_upload_page(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                ->visit(route('organizer.documents.create'))
                ->assertSee('403');
        });
    }
}
