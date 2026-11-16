<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

/**
 * TC.TambahBeasiswa.005 – Deadline sebelum tanggal mulai (Negative)
 *
 * Pre-condition : Admin berada di /admin/scholarships/create
 * Langkah       :
 *   1) Isi form valid; Tanggal Mulai = 2026-12-10, Deadline = 2026-12-01
 *   2) Klik Simpan → data ditolak, pesan error tanggal tampil
 *
 * Catatan: Input type="date" diisi lewat script() karena perilaku
 * type() pada input tanggal tidak konsisten antar OS/browser (EXTRA).
 */
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

                    // Step 1: Isi form valid, lalu set tanggal yang salah
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

                    // Tunggu pesan error tanggal tampil
                    // EXTRA: waitForText – tunggu pesan error tanggal tampil
                    $browser->waitForText('Deadline tidak boleh lebih awal dari tanggal mulai.', 10)
                            ->assertPathIs('/admin/scholarships/create');

                    // Nama tetap terisi (input lain tidak hilang)
                    // EXTRA: assertInputValue – memverifikasi old() value dipertahankan
                    $browser->assertInputValue('name', 'Beasiswa TC005 Deadline Salah');

                    // DB tidak bertambah
                    // EXTRA: query DB – verifikasi jumlah data di database tidak bertambah
                    $this->assertDatabaseCount('beasiswas', 0);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.005');
                });
            }
        );
    }
}
