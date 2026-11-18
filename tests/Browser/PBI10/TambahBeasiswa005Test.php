<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;


class TambahBeasiswa005Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_deadline_sebelum_tanggal_mulai(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.005',
            'Deadline sebelum tanggal mulai',
            'Negative',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    $this->fillValidForm($browser, [
                        'name'           => 'Beasiswa TC005 Deadline Salah',
                        'organizer_name' => 'Penyelenggara TC005',
                        'description'    => 'Deskripsi TC005.',
                        'level'          => 'S1',
                        'status'         => 'draft',
                        // Tanggal mulai SETELAH deadline (sengaja salah)
                        'start_date'     => '2026-12-10',
                        'end_date'       => '2026-12-01', // deadline < start_date
                    ]);

                    // Step 2: Klik Simpan
                    $browser->press('Simpan');

                    $browser->waitForText('Deadline tidak boleh lebih awal dari tanggal mulai.', 10)
                            ->assertPathIs('/admin/scholarships/create');

                    $browser->assertInputValue('name', 'Beasiswa TC005 Deadline Salah');

   
                    $this->assertDatabaseCount('beasiswas', 0);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.005');
                });
            }
        );
    }
}
