<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GantiDokumenTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', ['--seed' => true]);
    }
    public function testDoc04GantiDokumen(): void
    {
        $lama = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ktm_lama.pdf';
        file_put_contents($lama, "%PDF-1.4\n%%EOF");

        $baru = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ktm_baru.pdf';
        file_put_contents($baru, "%PDF-1.4\n%%EOF");

        $this->browse(function (Browser $browser) use ($lama, $baru) {
            $browser->visit('/profile/documents')
                ->select('document_type', 'ktm');

            $browser->script("document.getElementById('document').classList.remove('d-none');");

            $browser->attach('document', $lama)
                ->press('Upload Dokumen')
                ->waitForText('ktm_lama.pdf')
                ->assertSee('Dokumen Tersimpan (1)')
                ->assertSee('ktm_lama.pdf')
                ->select('document_type', 'ktm');

            $browser->script("document.getElementById('document').classList.remove('d-none');");

            $browser->attach('document', $baru)
                ->press('Upload Dokumen')
                ->waitForText('ktm_baru.pdf')
                ->assertSee('Dokumen Tersimpan (1)')
                ->assertSee('ktm_baru.pdf')
                ->assertDontSee('ktm_lama.pdf');
        });
    }
}
