<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;


class TambahBeasiswa004Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_field_wajib_kosong(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.004',
            'Field wajib kosong',
            'Negative',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    // Step 1: Tidak mengisi apapun, langsung klik Simpan
                    $browser->press('Simpan');

                    // Step 2: Tunggu hingga error validasi tampil setelah redirect kembali
                    $browser->waitFor('.alert-error', 10)
                            ->assertPathIs('/admin/scholarships/create');

                    $browser->assertSee('Terdapat kesalahan pada form');

          
                    $browser->assertSee('Nama beasiswa wajib diisi.')
                            ->assertSee('Penyelenggara wajib diisi.')
                            ->assertSee('Deskripsi wajib diisi.')
                            ->assertSee('Jenjang wajib dipilih.');

                 
                    $this->assertDatabaseCount('beasiswas', 0);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.004');
                });
            }
        );
    }
}
