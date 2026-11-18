<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;


class TambahBeasiswa001Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_tambah_beasiswa_data_lengkap(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.001',
            'Tambah beasiswa data lengkap',
            'Positive',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    $this->fillValidForm($browser, [
                        'name'           => 'Beasiswa Uji Otomatis 001',
                        'organizer_name' => 'Lembaga Dusk Testing',
                        'description'    => 'Deskripsi lengkap beasiswa otomatis TC001.',
                        'level'          => 'S1',
                        'category'       => 'Full Scholarship',
                        'amount'         => '10000000',
                        'quota'          => '50',
                        'start_date'     => '2026-12-01',
                        'end_date'       => '2026-12-31',
                        'source_url'     => 'https://example.com/beasiswa',
                        'status'         => 'draft',
                    ]);

         
                    $browser->attach('poster', $this->fixture('poster_1mb.jpg'))
                            ->waitFor('#poster-preview-card', 5);

                    $browser->press('Simpan');
                    // EXTRA: waitForText – tunggu redirect selesai dan pesan sukses muncul
                    $browser->waitForText('berhasil', 10)
                            ->assertPathIs('/admin/scholarships');

            
                    $this->assertDatabaseHas('beasiswas', [
                        'nama'          => 'Beasiswa Uji Otomatis 001',
                        'penyelenggara' => 'Lembaga Dusk Testing',
                        'status'        => 'draft',
                    ]);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.001');
                });
            }
        );
    }
}
