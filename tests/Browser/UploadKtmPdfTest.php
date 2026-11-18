<?php

namespace Tests\Browser;

use App\Models\ProfileDocument;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UploadKtmPdfTest extends DuskTestCase
{
    public function testDoc02UploadKtmPdf(): void
    {
        $user = User::first();

        $ktm = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ktm_test.pdf';
        file_put_contents($ktm, "%PDF-1.4\n%%EOF");

        $this->browse(function (Browser $browser) use ($user, $ktm) {
            $browser->visit('/profile/documents')
                ->select('document_type', 'ktm')
                ->assertSelected('document_type', 'ktm');
            $browser->script("document.getElementById('document').classList.remove('d-none');");
            $browser->attach('document', $ktm)
                ->assertSeeIn('#uploadText', 'ktm_test.pdf')
                ->press('Upload Dokumen')
                ->waitForText('Dokumen berhasil diunggah!')
                ->assertPathIs('/profile/documents')
                ->assertSee('Dokumen berhasil diunggah!')
                ->assertSee('Dokumen Tersimpan (1)')
                ->assertSee('Kartu Tanda Mahasiswa (KTM)')
                ->assertSee('ktm_test.pdf')
                ->assertPresent("a[title='Download']");

            $this->assertEquals(1, ProfileDocument::where('user_id', $user->id)->where('document_type', 'ktm')->count());
        });
    }
}
