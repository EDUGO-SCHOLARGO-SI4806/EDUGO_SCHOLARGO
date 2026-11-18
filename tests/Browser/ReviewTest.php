<?php

namespace Tests\Browser;

use App\Models\User;
use Database\Seeders\AccountSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReviewTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccountSeeder::class);
    }

    // TC.Review.001 - Positive
    public function test_review_001_setujui_penyelenggara(): void
    {
        $admin = User::where('email', 'superadmin@scholargo.test')->first();
        $organizer = User::where('email', 'penyelenggara.menunggu@scholargo.test')->first();

        $this->browse(function (Browser $browser) use ($admin, $organizer) {
            $browser->loginAs($admin)
                ->visit('/admin/accounts/'.$organizer->id)
                ->assertSee('Dokumen Legalitas')
                ->assertSee('Keputusan Verifikasi')
                ->screenshot('TC.Review.001-sebelum')
                ->press('Setujui')
                ->assertSee('Disetujui')
                ->assertDontSee('Keputusan Verifikasi')
                ->screenshot('TC.Review.001-sesudah');
        });
    }

    // TC.Review.002 - Negative
    public function test_review_002_tolak_tanpa_catatan(): void
    {
        $admin = User::where('email', 'superadmin@scholargo.test')->first();
        $organizer = User::where('email', 'penyelenggara.menunggu@scholargo.test')->first();

        $this->browse(function (Browser $browser) use ($admin, $organizer) {
            $browser->loginAs($admin)
                ->visit('/admin/accounts/'.$organizer->id)
                ->press('Tolak')
                ->assertSee('wajib')
                ->assertSee('Keputusan Verifikasi')
                ->screenshot('TC.Review.002-error');
        });
    }
}