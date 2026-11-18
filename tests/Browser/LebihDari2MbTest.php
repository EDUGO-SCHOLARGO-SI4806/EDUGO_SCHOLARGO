<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LebihDari2MbTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', ['--seed' => true]);
    }
    public function testDoc06LebihDari2Mb(): void
    {
        $besar = sys_get_temp_dir().DIRECTORY_SEPARATOR.'besar.pdf';
        file_put_contents($besar, str_pad("%PDF-1.4\n%%EOF", 2097153, ' '));

        $this->browse(function (Browser $browser) use ($besar) {
            $browser->visit('/profile/documents')
                ->select('document_type', 'transkrip');

            $browser->script("window.__alerts = []; window.alert = function (pesan) { window.__alerts.push(pesan); };");
            $browser->script("document.getElementById('document').classList.remove('d-none');");

            $browser->attach('document', $besar);

            $this->assertContains(
                'Ukuran file melebihi 2 MB. Silakan pilih file yang lebih kecil.',
                $browser->script('return window.__alerts;')[0]
            );
            $this->assertSame('', $browser->script("return document.getElementById('document').value;")[0]);

            $browser->assertSeeIn('#uploadText', 'Klik atau seret file ke sini');
        });
    }
}
