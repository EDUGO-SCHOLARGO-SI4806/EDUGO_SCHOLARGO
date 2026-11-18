<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FilterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_filterPositive(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/beasiswa')
                ->assertPathIs('/beasiswa')
                ->select('jenjang', 'S2')
                ->assertSelected('jenjang', 'S2')
                ->press('Cari Beasiswa')
                ->assertQueryStringHas('jenjang', 'S2')
                ->assertSee('S2')
                ->assertPresent('.result-info')
                ->assertPresent('.card');
        });
    }
    public function test_filterNegative(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/beasiswa')
                ->assertPathIs('/beasiswa')
                ->select('batas_waktu', '7')
                ->assertSelected('batas_waktu', '7')
                ->press('Cari Beasiswa')
                ->assertQueryStringHas('batas_waktu', '7')
                ->assertSee('Tidak ada beasiswa yang cocok');
        });
    }
}