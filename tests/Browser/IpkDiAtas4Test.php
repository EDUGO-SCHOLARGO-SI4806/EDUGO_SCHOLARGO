<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IpkDiAtas4Test extends DuskTestCase
{
    public function testEdit03IpkDiAtas4(): void
    {
        $user = User::first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/profile/edit');
            $browser->script("document.querySelector('form[action\$=\"/profile/update\"]').setAttribute('novalidate', 'novalidate');");
            $browser->type('kampus', 'Kampus Uji')
                ->type('ipk', '4.01')
                ->assertInputValue('kampus', 'Kampus Uji')
                ->assertInputValue('ipk', '4.01')
                ->press('Simpan Perubahan')
                ->waitForText('IPK maksimal 4.00')
                ->assertPathIs('/profile/edit')
                ->assertSee('IPK maksimal 4.00')
                ->assertPresent('#ipk.is-invalid')
                ->assertInputValue('kampus', 'Kampus Uji');
        });
    }
}
