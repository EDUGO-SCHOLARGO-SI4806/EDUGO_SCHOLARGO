<?php

namespace Tests\Browser;

use App\Models\User;
use Database\Seeders\AccountSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AkunTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccountSeeder::class);
    }

    // TC.Akun.001 - Positive
    public function test_akun_001_lihat_daftar_dan_detail(): void
    {
        $admin = User::where('email', 'superadmin@scholargo.test')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/accounts')
                ->assertSee('Manajemen Akun')
                ->assertSee('Peran')
                ->assertSee('Status Verifikasi')
                ->screenshot('TC.Akun.001-daftar')
                ->clickLink('2')
                ->assertQueryStringHas('page', '2')
                ->clickLink('Detail')
                ->assertPathBeginsWith('/admin/accounts/')
                ->assertSee('Email')
                ->screenshot('TC.Akun.001-detail');
        });
    }

    // TC.Akun.002 - Negative
    public function test_akun_002_mahasiswa_ditolak(): void
    {
        $mahasiswa = User::where('email', 'budi@mahasiswa.test')->first();

        $this->browse(function (Browser $browser) use ($mahasiswa) {
            $browser->loginAs($mahasiswa)
                ->visit('/admin/accounts')
                ->assertSee('403')
                ->assertDontSee('Status Verifikasi')
                ->screenshot('TC.Akun.002-403');
        });
    }
}