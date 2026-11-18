<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;


class TambahBeasiswa006Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_poster_format_salah(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.006',
            'Poster format salah (PDF)',
            'Negative',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    // Step 1: Isi form valid
                    $this->fillValidForm($browser);

                    $browser->attach('poster', $this->fixture('dokumen.pdf'));

                    // Step 2: Klik Simpan
                    $browser->press('Simpan');

               
                    $browser->waitForText('Format poster harus JPEG, PNG, JPG, atau WEBP.', 10)
                            ->assertPathIs('/admin/scholarships/create');

    
                    $this->assertDatabaseCount('beasiswas', 0);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.006');
                });
            }
        );
    }
}
