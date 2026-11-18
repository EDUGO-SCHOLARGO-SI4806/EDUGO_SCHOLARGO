<?php

namespace Tests\Browser\PBI11;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class DaftarBeasiswa006Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        // Tidak ada data beasiswa (DatabaseMigrations mereset tabel beasiswas)

        $this->runCase(
            'PBI #11',
            'TC.DaftarBeasiswa.006',
            'Daftar kosong — pesan "Belum ada data beasiswa" tampil tanpa error',
            'Negative',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships');

                    // Verifikasi pesan saat tidak ada data beasiswa
                    $browser->assertSee('Belum ada data beasiswa yang ditambahkan.');

                    $this->shot($browser, 'PBI11_TC.DaftarBeasiswa.006');
                });
            }
        );
    }
}
