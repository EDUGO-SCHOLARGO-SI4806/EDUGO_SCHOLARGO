<?php

namespace Tests\Browser\PBI12;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class UbahBeasiswa001Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        $scholarship = $this->makeScholarship([
            'nama'          => 'Nama Lama Dusk',
            'penyelenggara' => 'Lembaga Lama',
            'jenjang'       => 'S1',
            'status'        => 'draft',
        ]);

        $this->runCase(
            'PBI #12',
            'TC.UbahBeasiswa.001',
            'Ubah nama beasiswa dan deadline, verifikasi data terupdate di detail dan daftar',
            'Positive',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    // Buka halaman detail beasiswa lalu klik tombol Ubah/Edit
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/' . $scholarship->id)
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id);

                    // Klik link Edit Beasiswa untuk membuka form ubah
                    $browser->clickLink('Edit Beasiswa');
                    $browser->assertPathIs('/admin/scholarships/' . $scholarship->id . '/edit');

                    // Verifikasi form terisi data lama
                    // EXTRA: assertInputValue – memverifikasi form edit terisi nilai nama beasiswa lama
                    $browser->assertInputValue('name', 'Nama Lama Dusk');

                    // Ganti nilai field Nama
                    // EXTRA: script – mengosongkan nilai input sebelum diisi nilai baru
                    $browser->script("document.querySelector('[name=name]').value=''");
                    $browser->type('name', 'Nama Baru Dusk 001');

                    // Ganti deadline via script (input date lebih konsisten lintas OS)
                    // EXTRA: script – mengisi input[type=date] secara konsisten lintas platform
                    $browser->script("document.querySelector('[name=end_date]').value='2027-06-30'");

                    // Submit form edit
                    $browser->press('Simpan Perubahan');

                    // Verifikasi pesan sukses tampil dan redirect ke detail beasiswa
                    // EXTRA: waitForText – menunggu redirect dan pesan sukses pembaruan data tampil
                    $browser->waitForText('Data beasiswa berhasil diperbarui.', 10)
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id)
                            ->assertSee('Nama Baru Dusk 001');

                    // Verifikasi halaman daftar juga menampilkan data baru
                    $browser->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships')
                            ->assertSee('Nama Baru Dusk 001');

                    // EXTRA: query DB – memverifikasi perubahan nama tersimpan di database
                    $this->assertDatabaseHas('beasiswas', [
                        'id'   => $scholarship->id,
                        'nama' => 'Nama Baru Dusk 001',
                    ]);

                    $this->shot($browser, 'PBI12_TC.UbahBeasiswa.001');
                });
            }
        );
    }
}
