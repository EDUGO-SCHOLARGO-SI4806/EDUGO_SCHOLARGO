<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CariTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_cariPositive(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/beasiswa')
                ->assertPathIs('/beasiswa')
                ->assertSee('ScholarGO')
                ->type('q', 'Yayasan')
                ->assertInputValue('q', 'Yayasan')
                ->press('Cari Beasiswa')
                ->assertSee('Yayasan')
                ->assertPresent('.result-info')
                ->assertPresent('.card');
        });
    }

    public function test_cariNegative(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/beasiswa')
                ->assertPathIs('/beasiswa')
                ->type('q', 'Halo')
                ->assertInputValue('q', 'Halo')
                ->press('Cari Beasiswa')
                ->assertSee('Tidak ada beasiswa yang cocok')
                ->assertSee('Coba ubah atau kurangi filter');
        });
    }
}