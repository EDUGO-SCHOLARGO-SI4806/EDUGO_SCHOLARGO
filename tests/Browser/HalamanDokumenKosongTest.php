<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HalamanDokumenKosongTest extends DuskTestCase
{
    public function testDoc01HalamanKosong(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile/documents')
                ->assertTitle('Upload Dokumen Profil - ScholarGo')
                ->assertSee('Ketentuan Upload')
                ->assertSee('Format: PDF, JPG, JPEG, PNG (Maks. 2 MB)')
                ->assertSee('Dokumen Tersimpan (0)')
                ->assertSee('Belum ada dokumen yang diunggah');
        });
    }
}
