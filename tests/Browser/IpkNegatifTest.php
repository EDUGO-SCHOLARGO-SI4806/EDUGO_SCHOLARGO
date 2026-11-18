<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IpkNegatifTest extends DuskTestCase
{
    public function testEdit04IpkNegatif(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile/edit');
            $browser->script("document.querySelector('form[action\$=\"/profile/update\"]').setAttribute('novalidate', 'novalidate');");
            $browser->type('ipk', '-0.01')
                ->assertInputValue('ipk', '-0.01')
                ->press('Simpan Perubahan')
                ->waitForText('IPK minimal 0.00')
                ->assertPathIs('/profile/edit')
                ->assertSee('IPK minimal 0.00')
                ->assertPresent('#ipk.is-invalid');
        });
    }
}
