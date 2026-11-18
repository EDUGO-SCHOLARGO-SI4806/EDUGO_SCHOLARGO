<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LihatDaftarTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_lihatDaftarPositive(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/beasiswa')
                ->assertSee('ScholarGO')
                ->assertSee('Pilihan beasiswa untukmu')
                ->assertSee('peluang tersedia')
                ->assertPresent('.grid')
                ->assertPresent('.card');
        });
    }

    public function test_lihatDaftarNegative(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/beasiswa')
                ->assertPathIs('/beasiswa')
                ->assertDontSee('Beasiswa Global Leaders 2026');
        });
    }
}