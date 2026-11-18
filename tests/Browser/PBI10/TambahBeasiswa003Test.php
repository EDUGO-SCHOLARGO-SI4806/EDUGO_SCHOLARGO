<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;


class TambahBeasiswa003Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_tambah_beasiswa_status_published(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.003',
            'Tambah beasiswa status Published',
            'Positive',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    // Step 1: Isi form valid, pilih Status = Published
                    $this->fillValidForm($browser, [
                        'name'           => 'Beasiswa Published TC003',
                        'organizer_name' => 'Penyelenggara TC003',
                        'description'    => 'Beasiswa dengan status published untuk TC003.',
                        'level'          => 'S2',
                        'status'         => 'published',  // <<< Published
                    ]);

                    // Step 2: Klik Simpan
                    $browser->press('Simpan');
                    // EXTRA: waitForText – tunggu redirect selesai dan pesan sukses tampil
                    $browser->waitForText('berhasil', 10)
                            ->assertPathIs('/admin/scholarships');

                    // Verifikasi status = published
                    // EXTRA: query DB – verifikasi status = published
                    $this->assertDatabaseHas('beasiswas', [
                        'nama'   => 'Beasiswa Published TC003',
                        'status' => 'published',
                    ]);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.003');
                });
            }
        );
    }
}
