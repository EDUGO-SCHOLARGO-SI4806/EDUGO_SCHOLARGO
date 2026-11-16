<?php

namespace Tests\Browser\PBI10;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class TambahBeasiswa009Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.009',
            'Link sumber tidak valid',
            'Negative',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create');

                    // Isi field wajib valid terlebih dahulu
                    $this->fillValidForm($browser);

                    // Isi source_url dengan nilai yang bukan URL valid
                    $browser->type('source_url', 'abc');

                    // Submit form
                    $browser->click('#btn-submit');

                    // Tunggu pesan error server untuk URL tidak valid
                    // EXTRA: waitForText – tunggu pesan error server untuk URL tidak valid
                    $browser->waitForText('Link sumber harus berupa URL yang valid.', 10)
                            ->assertSee('Link sumber harus berupa URL yang valid.');

                    // Pastikan tidak ada data tersimpan di database
                    // EXTRA: query DB – pastikan tidak ada data tersimpan di database
                    $this->assertDatabaseCount('beasiswas', 0);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.009');
                });
            }
        );
    }
}
