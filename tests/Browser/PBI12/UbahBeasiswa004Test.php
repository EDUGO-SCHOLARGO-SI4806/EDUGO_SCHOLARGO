<?php

namespace Tests\Browser\PBI12;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class UbahBeasiswa004Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        $scholarship = $this->makeScholarship([
            'nama'   => 'Beasiswa Nama Wajib',
            'jenjang'=> 'S1',
            'status' => 'draft',
        ]);

        $this->runCase(
            'PBI #12',
            'TC.UbahBeasiswa.004',
            'Kosongkan field wajib Nama: data ditolak, pesan error tampil, data lama di DB tidak berubah',
            'Negative',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/' . $scholarship->id . '/edit')
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id . '/edit');

                    // Kosongkan field nama via script agar pasti kosong
                    // EXTRA: script – mengosongkan nilai input secara andal
                    $browser->script("document.querySelector('[name=name]').value=''");

                    // Submit form edit
                    $browser->press('Simpan Perubahan');

                    // Verifikasi pesan error validasi muncul
                    // EXTRA: waitForText – menunggu respon server dan pesan error validasi tampil
                    $browser->waitForText('Nama beasiswa wajib diisi.', 10)
                            ->assertSee('Nama beasiswa wajib diisi.');

                    // EXTRA: query DB – memverifikasi data lama di database tidak berubah
                    $this->assertDatabaseHas('beasiswas', [
                        'id'   => $scholarship->id,
                        'nama' => 'Beasiswa Nama Wajib',
                    ]);

                    $this->shot($browser, 'PBI12_TC.UbahBeasiswa.004');
                });
            }
        );
    }
}
