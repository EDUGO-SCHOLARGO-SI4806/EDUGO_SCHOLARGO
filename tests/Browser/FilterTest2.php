<?php

namespace Tests\Browser;

use App\Models\User;
use Database\Seeders\AccountSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FilterTest2 extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccountSeeder::class);
    }

    // TC.Filter.001 - Positive
    public function test_filter_001_cari_dan_filter(): void
    {
        $admin = User::where('email', 'superadmin@scholargo.test')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/accounts')
                ->type('q', 'budi')
                ->press('Terapkan')
                ->waitForText('Budi Santoso')
                ->assertDontSee('Citra Lestari')
                ->screenshot('TC.Filter.001-cari')
                ->clickLink('Reset')
                ->waitUntil("document.querySelector('input[name=q]').value === ''")
                ->assertDontSee('Tidak ada akun yang cocok')
                ->select('role', 'penyelenggara')
                ->select('status', 'pending')
                ->press('Terapkan')
                ->waitUntil("new URLSearchParams(location.search).get('status') === 'pending'")
                ->assertDontSee('Budi Santoso')
                ->assertSee('Menunggu')
                ->screenshot('TC.Filter.001-filter');
        });
    }

    // TC.Filter.002 - Negative
    public function test_filter_002_cari_tidak_ada_hasil(): void
    {
        $admin = User::where('email', 'superadmin@scholargo.test')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/accounts')
                ->type('q', 'zzzzzz')
                ->press('Terapkan')
                ->waitForText('Tidak ada akun yang cocok')
                ->screenshot('TC.Filter.002-kosong')
                ->clickLink('Reset')
                ->waitUntil("document.querySelector('input[name=q]').value === ''")
                ->assertDontSee('Tidak ada akun yang cocok');
        });
    }
}