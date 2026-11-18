<?php

namespace Tests\Browser\PBI11;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class DaftarBeasiswa007Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        $this->runCase(
            'PBI #11',
            'TC.DaftarBeasiswa.007',
            'Akses detail ID tidak ada (9999) — halaman 404 tampil',
            'Negative',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/9999');

                    // Verifikasi halaman 404 tampil
                    $browser->assertSee('404');

                    $this->shot($browser, 'PBI11_TC.DaftarBeasiswa.007');
                });
            }
        );
    }
}
