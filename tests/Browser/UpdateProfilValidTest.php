<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateProfilValidTest extends DuskTestCase
{
    public function testEdit02UpdateValid(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile/edit')
                ->assertTitle('Edit Profil - ScholarGo')
                ->type('phone', '081398765432')
                ->type('alamat', 'Jl. Telekomunikasi No. 1, Bandung')
                ->type('kampus', 'Universitas Telkom')
                ->select('jenjang', 'S2')
                ->type('semester', '6')
                ->type('ipk', '3.90');

            $browser->script("document.getElementById('tanggal_lahir').value = '2003-01-20';");

            $browser->assertInputValue('phone', '081398765432')
                ->assertInputValue('tanggal_lahir', '2003-01-20')
                ->assertInputValue('alamat', 'Jl. Telekomunikasi No. 1, Bandung')
                ->assertInputValue('kampus', 'Universitas Telkom')
                ->assertSelected('jenjang', 'S2')
                ->assertInputValue('semester', '6')
                ->assertInputValue('ipk', '3.90')
                ->press('Simpan Perubahan')
                ->waitForLocation('/profile')
                ->assertPathIs('/profile')
                ->assertSee('Data profil berhasil diperbarui!')
                ->assertSee('081398765432')
                ->assertSee('20 January 2003')
                ->assertSee('Universitas Telkom')
                ->assertSee('S2')
                ->assertSee('Semester 6')
                ->assertSee('3.90');
        });
    }
}
