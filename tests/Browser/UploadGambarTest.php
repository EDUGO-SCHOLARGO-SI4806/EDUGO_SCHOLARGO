<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UploadGambarTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', ['--seed' => true]);
    }
    public function testDoc03UploadGambar(): void
    {
        $png = sys_get_temp_dir().DIRECTORY_SEPARATOR.'foto_test.png';
        file_put_contents($png, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADElEQVR42mP4//8/AAX+Av4zEpUUAAAAAElFTkSuQmCC'));

        $jpg = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ktp_test.jpg';
        file_put_contents($jpg, base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDABALDA4MChAODQ4SERATGCgaGBYWGDEjJR0oOjM9PDkzODdASFxOQERXRTc4UG1RV19iZ2hnPk1xeXBkeFxlZ2P/2wBDARESEhgVGC8aGi9jQjhCY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2P/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AKAAD//Z'));

        $this->browse(function (Browser $browser) use ($png, $jpg) {
            $browser->visit('/profile/documents')
                ->select('document_type', 'foto');

            $browser->script("document.getElementById('document').classList.remove('d-none');");

            $browser->attach('document', $png)
                ->press('Upload Dokumen')
                ->waitForText('Dokumen berhasil diunggah!')
                ->select('document_type', 'ktp');

            $browser->script("document.getElementById('document').classList.remove('d-none');");

            $browser->attach('document', $jpg)
                ->press('Upload Dokumen')
                ->waitForText('Dokumen Tersimpan (2)')
                ->assertSee('foto_test.png')
                ->assertSee('ktp_test.jpg')
                ->assertPresent('.doc-icon.png')
                ->assertPresent('.doc-icon.jpg');
        });
    }
}
