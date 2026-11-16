<?php

namespace Tests\Browser\PBI12;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class UbahBeasiswa003Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        $scholarship = $this->makeScholarship([
            'nama'   => 'Beasiswa Draft Ubah Status',
            'jenjang'=> 'S1',
            'status' => 'draft',
        ]);

        $this->runCase(
            'PBI #12',
            'TC.UbahBeasiswa.003',
            'Ubah status dari Draft menjadi Published, verifikasi status di detail dan daftar',
            'Positive',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/' . $scholarship->id . '/edit')
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id . '/edit');

                    // Pilih Status Published
                    $browser->select('status', 'published');

                    // Submit form edit
                    $browser->press('Simpan Perubahan');

                    // Tunggu pesan sukses dan verifikasi halaman detail menampilkan Published
                    // EXTRA: waitForText – menunggu redirect dan pesan sukses tampil
                    $browser->waitForText('Data beasiswa berhasil diperbarui.', 10)
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id)
                            ->assertSee('Published');

                    // Buka halaman daftar dan verifikasi status Published tampil
                    $browser->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships')
                            ->assertSee('Published');

                    // EXTRA: query DB – memverifikasi status published tersimpan di database
                    $this->assertDatabaseHas('beasiswas', [
                        'id'     => $scholarship->id,
                        'status' => 'published',
                    ]);

                    $this->shot($browser, 'PBI12_TC.UbahBeasiswa.003');
                });
            }
        );
    }
}
