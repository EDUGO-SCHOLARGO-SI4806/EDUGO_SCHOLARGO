<?php

namespace Tests\Browser\PBI10;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class TambahBeasiswa008Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.008',
            'Nominal dan kuota tidak valid',
            'Negative',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create');

                    // Isi field wajib valid terlebih dahulu (tidak termasuk amount & quota)
                    $this->fillValidForm($browser);

                    // Isi field amount dengan nilai negatif dan quota dengan 0
                    // EXTRA: clear – mengosongkan input sebelum type
                    $browser->clear('amount')
                            ->type('amount', '-1')
                            ->clear('quota')
                            ->type('quota', '0');

                    // Submit form
                    $browser->click('#btn-submit');

                    // Tunggu pesan error server
                    // EXTRA: waitForText – tunggu pesan error server muncul
                    $browser->waitForText('Nominal tidak boleh negatif.', 10)
                            ->assertSee('Nominal tidak boleh negatif.')
                            ->assertSee('Kuota minimal 1.');

                    // Pastikan tidak ada data tersimpan di database
                    // EXTRA: query DB – pastikan tidak ada data tersimpan di database
                    $this->assertDatabaseCount('beasiswas', 0);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.008');
                });
            }
        );
    }
}
