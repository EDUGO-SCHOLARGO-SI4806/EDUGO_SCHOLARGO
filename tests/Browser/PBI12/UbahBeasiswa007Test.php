<?php

namespace Tests\Browser\PBI12;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class UbahBeasiswa007Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        $this->runCase(
            'PBI #12',
            'TC.UbahBeasiswa.007',
            'Akses halaman edit dengan ID tidak ada (9999) — halaman 404 tampil',
            'Negative',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/9999/edit');

                    // Verifikasi halaman 404 tampil untuk model binding yang tidak ditemukan
                    $browser->assertSee('404');

                    $this->shot($browser, 'PBI12_TC.UbahBeasiswa.007');
                });
            }
        );
    }
}
