<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IpkBukanAngkaTest extends DuskTestCase
{
    public function testEdit05IpkBukanAngka(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile/edit');
            $browser->script("document.getElementById('ipk').type = 'text';");
            $browser->type('ipk', 'abc')
                ->assertInputValue('ipk', 'abc')
                ->press('Simpan Perubahan')
                ->waitForText('IPK harus berupa angka')
                ->assertPathIs('/profile/edit')
                ->assertSee('IPK harus berupa angka')
                ->assertPresent('#ipk.is-invalid');
        });
    }
}
