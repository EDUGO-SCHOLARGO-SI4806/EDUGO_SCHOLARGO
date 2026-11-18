<?php

namespace Tests\Browser\PBI11;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class DaftarBeasiswa005Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        $this->runCase(
            'PBI #11',
            'TC.DaftarBeasiswa.005',
            'Navigasi ke form tambah beasiswa dari halaman daftar',
            'Positive',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships');

                    // Klik tombol "Tambah Beasiswa"
                    $browser->clickLink('Tambah Beasiswa');

                    // Verifikasi form tambah beasiswa terbuka
                    $browser->assertPathIs('/admin/scholarships/create')
                            ->assertSee('Tambah Beasiswa');

                    $this->shot($browser, 'PBI11_TC.DaftarBeasiswa.005');
                });
            }
        );
    }
}
