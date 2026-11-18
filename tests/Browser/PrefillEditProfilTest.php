<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PrefillEditProfilTest extends DuskTestCase
{
    public function testEdit01Prefill(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile/edit')
                ->assertTitle('Edit Profil - ScholarGo')
                ->assertInputValue('phone', '081234567890')
                ->assertInputValue('tanggal_lahir', '2004-05-15')
                ->assertInputValue('alamat', 'Bandung, Jawa Barat')
                ->assertInputValue('nim', '102022400170')
                ->assertInputValue('kampus', 'Telkom University')
                ->assertInputValue('program_studi', 'Informatika')
                ->assertSelected('jenjang', 'S1')
                ->assertInputValue('semester', '5')
                ->assertInputValue('ipk', '3.75');
        });
    }
}