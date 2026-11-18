<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;


class TambahBeasiswa007Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_poster_lebih_dari_2mb(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.007',
            'Poster lebih dari 2 MB',
            'Negative',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    // Step 1: Isi form valid
                    $this->fillValidForm($browser);

                    // Attach file 3 MB – EXTRA: attach()
                    $browser->attach('poster', $this->fixture('poster_3mb.jpg'));

                    $browser->waitFor('#poster-client-error', 5);
                    // EXTRA: assertSeeIn – verifikasi teks error client-side pada elemen tertentu
                    $browser->assertSeeIn('#poster-client-error', 'melebihi batas maksimal 2 MB');

       
                    $browser->press('Simpan');
                    // EXTRA: waitForText – tunggu redirect selesai ke halaman daftar
                    $browser->waitForText('berhasil', 10)
                            ->assertPathIs('/admin/scholarships');

      
                    $this->assertDatabaseHas('beasiswas', [
                        'nama'   => 'Beasiswa Uji Otomatis',
                        'poster' => null,
                    ]);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.007');
                });
            }
        );
    }
}
