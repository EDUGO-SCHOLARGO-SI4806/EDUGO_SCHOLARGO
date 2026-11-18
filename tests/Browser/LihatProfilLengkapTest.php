<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LihatProfilLengkapTest extends DuskTestCase
{
    public function testShow01Lengkap(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile')
                ->assertTitle('Profil Saya - ScholarGo')
                ->assertSee('Muhammad Rafi Fazlullah Pasya')
                ->assertSee('rafi@scholargo.test')
                ->assertSee('081234567890')
                ->assertSee('15 May 2004')
                ->assertSee('Bandung, Jawa Barat')
                ->assertSee('102022400170')
                ->assertSee('Telkom University')
                ->assertSee('Informatika')
                ->assertSee('S1')
                ->assertSee('Semester 5')
                ->assertPresent('.sg-badge-success')
                ->assertSeeIn('.sg-badge-success', '3.75')
                ->assertSee('100%');
        });
    }
}