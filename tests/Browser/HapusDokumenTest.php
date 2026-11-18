<?php

namespace Tests\Browser;

use App\Models\ProfileDocument;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HapusDokumenTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', ['--seed' => true]);
    }
    public function testDoc10Hapus(): void
    {
        $user = User::first();

        Storage::disk('public')->put('profile_documents/hapus_saya.pdf', "%PDF-1.4\n%%EOF");
        ProfileDocument::create([
            'user_id' => $user->id,
            'document_type' => 'ktm',
            'original_name' => 'hapus_saya.pdf',
            'file_path' => 'profile_documents/hapus_saya.pdf',
            'file_extension' => 'pdf',
            'file_size' => 14,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/profile/documents')
                ->click("button[title='Hapus']")
                ->waitForDialog()
                ->assertDialogOpened('Yakin ingin menghapus dokumen ini?')
                ->acceptDialog()
                ->waitForText('Dokumen berhasil dihapus!')
                ->assertSee('Dokumen berhasil dihapus!')
                ->assertSee('Dokumen Tersimpan (0)')
                ->assertDontSee('hapus_saya.pdf');

            $this->assertDatabaseCount('profile_documents', 0);
        });
    }
}
