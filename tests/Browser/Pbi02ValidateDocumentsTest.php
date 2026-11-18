<?php

namespace Tests\Browser;

use App\Enums\OrganizerDocumentType;
use Laravel\Dusk\Browser;

/**
 * PBI-02 (FR-01) - Sebagai penyelenggara beasiswa, saya ingin sistem memvalidasi kelengkapan
 * jenis dokumen wajib serta format dan ukuran file (PDF/JPG, maks. 2 MB) dengan pesan error
 * yang jelas agar tidak salah unggah.
 */
class Pbi02ValidateDocumentsTest extends OrganizerBrowserTestCase
{
    public function test_empty_form_shows_required_message_for_every_field(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'))
                ->click('@submit-upload')
                ->assertPathIs('/organizer/documents/upload')
                ->assertPresent('@error-summary')
                ->assertSeeIn('@error-institution_name', 'Nama lembaga wajib diisi')
                ->assertSeeIn('@error-representative_name', 'Nama perwakilan wajib diisi');

            foreach (OrganizerDocumentType::cases() as $type) {
                $browser->assertSeeIn('@error-'.$type->value, $type->label().' wajib diunggah');
            }
        });

        $this->assertDatabaseCount('organizer_profiles', 0);
        $this->assertDatabaseCount('organizer_documents', 0);
    }

    public function test_missing_document_type_is_rejected_and_nothing_is_saved(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'));

            $this->fillInstitutionData($browser);
            $this->attachValidDocuments($browser, skip: [OrganizerDocumentType::SuratKuasa]);

            $browser->click('@submit-upload')
                ->assertPathIs('/organizer/documents/upload')
                ->assertSeeIn('@error-surat_kuasa', 'wajib diunggah')
                ->assertMissing('@error-akta_pendirian')
                ->assertMissing('@error-sk_pengesahan')
                // isian teks tidak hilang setelah error
                ->assertInputValue('institution_name', 'Yayasan Cendekia Nusantara')
                ->assertInputValue('representative_name', 'Dimas Prakoso');
        });

        // Pengajuan tidak boleh tersimpan sebagian.
        $this->assertDatabaseCount('organizer_profiles', 0);
        $this->assertDatabaseCount('organizer_documents', 0);
    }

    public function test_unsupported_file_format_shows_clear_message(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'));

            $this->fillInstitutionData($browser);
            $this->attachValidDocuments($browser, skip: [OrganizerDocumentType::AktaPendirian]);
            $browser->attach('documents[akta_pendirian]', $this->textFixture('akta.txt'));

            $browser->click('@submit-upload')
                ->assertPathIs('/organizer/documents/upload')
                ->assertSeeIn('@error-akta_pendirian', 'harus berformat PDF atau JPG');
        });

        $this->assertDatabaseCount('organizer_documents', 0);
    }

    public function test_file_larger_than_two_megabytes_shows_clear_message(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'));

            $this->fillInstitutionData($browser);
            $this->attachValidDocuments($browser, skip: [OrganizerDocumentType::SkPengesahan]);
            // ± 2,6 MB
            $browser->attach('documents[sk_pengesahan]', $this->pdfFixture('sk-terlalu-besar.pdf', paddingKb: 2600));

            $browser->click('@submit-upload')
                ->assertPathIs('/organizer/documents/upload')
                ->assertSeeIn('@error-sk_pengesahan', 'maksimal 2 MB');
        });

        $this->assertDatabaseCount('organizer_documents', 0);
    }

    public function test_file_just_below_two_megabytes_is_accepted(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'));

            $this->fillInstitutionData($browser);
            $this->attachValidDocuments($browser, skip: [OrganizerDocumentType::NibNpwp]);
            // ± 1,9 MB (masih di bawah batas)
            $browser->attach('documents[nib_npwp]', $this->pdfFixture('npwp-hampir-2mb.pdf', paddingKb: 1900));

            $browser->click('@submit-upload')
                ->assertPathIs('/organizer/documents')
                ->assertPresent('@flash-success');
        });

        $this->assertDatabaseCount('organizer_documents', count(OrganizerDocumentType::cases()));
    }

    public function test_every_invalid_field_is_reported_at_once(): void
    {
        $organizer = $this->createOrganizer();

        $this->browse(function (Browser $browser) use ($organizer) {
            $browser->loginAs($organizer)
                ->visit(route('organizer.documents.create'));

            $this->fillInstitutionData($browser);
            $this->attachValidDocuments($browser, skip: [
                OrganizerDocumentType::AktaPendirian,
                OrganizerDocumentType::SkPengesahan,
            ]);
            $browser->attach('documents[akta_pendirian]', $this->textFixture('akta.txt'))
                ->attach('documents[sk_pengesahan]', $this->pdfFixture('sk-besar.pdf', paddingKb: 2600));

            $browser->click('@submit-upload')
                ->assertSeeIn('@error-akta_pendirian', 'harus berformat PDF atau JPG')
                ->assertSeeIn('@error-sk_pengesahan', 'maksimal 2 MB')
                ->assertMissing('@error-nib_npwp');
        });
    }
}