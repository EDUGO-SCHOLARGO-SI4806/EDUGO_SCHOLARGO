<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

/**
 * TC.TambahBeasiswa.002 – Tambah beasiswa field wajib saja (Positive)
 *
 * Pre-condition : Admin berada di /admin/scholarships/create
 * Langkah       :
 *   1) Isi HANYA field wajib (Nama, Penyelenggara, Deskripsi, Jenjang, Status)
 *   2) Klik Simpan → data tersimpan, field opsional kosong, tidak error
 */
class TambahBeasiswa002Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_tambah_beasiswa_field_wajib_saja(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.002',
            'Tambah beasiswa field wajib saja',
            'Positive',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    // Step 1: Isi HANYA field wajib, field opsional dibiarkan kosong
                    $this->fillValidForm($browser, [
                        'name'           => 'Beasiswa Wajib Saja TC002',
                        'organizer_name' => 'Penyelenggara TC002',
                        'description'    => 'Deskripsi minimal field wajib saja.',
                        'level'          => 'D3',
                        'status'         => 'draft',
                        // opsional tidak diisi (null)
                        'category'   => null,
                        'amount'     => null,
                        'quota'      => null,
                        'start_date' => null,
                        'end_date'   => null,
                        'source_url' => null,
                    ]);

                    // Step 2: Klik Simpan
                    $browser->press('Simpan');
                    // EXTRA: waitForText – tunggu redirect selesai dan pesan sukses tampil
                    $browser->waitForText('berhasil', 10)
                            ->assertPathIs('/admin/scholarships');

                    // Verifikasi: tersimpan, field opsional null
                    // EXTRA: query DB – verifikasi tersimpan, field opsional null
                    $this->assertDatabaseHas('beasiswas', [
                        'nama'         => 'Beasiswa Wajib Saja TC002',
                        'penyelenggara'=> 'Penyelenggara TC002',
                        'nominal'      => null,
                        'kuota'        => null,
                        'poster'       => null,
                        'link_sumber'  => null,
                    ]);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.002');
                });
            }
        );
    }
}
