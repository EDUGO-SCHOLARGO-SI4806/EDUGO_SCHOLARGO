<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SemesterDi14Test extends DuskTestCase
{
    public function testEdit06SemesterDi14(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile/edit');
            $browser->script("document.querySelector('form[action\$=\"/profile/update\"]').setAttribute('novalidate', 'novalidate');");
            $browser->type('semester', '15')
                ->assertInputValue('semester', '15')
                ->press('Simpan Perubahan')
                ->waitForText('Semester maksimal 14')
                ->assertPathIs('/profile/edit')
                ->assertSee('Semester maksimal 14')
                ->assertPresent('#semester.is-invalid');
        });
    }
}
