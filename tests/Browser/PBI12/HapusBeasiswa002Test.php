<?php

namespace Tests\Browser\PBI12;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class HapusBeasiswa002Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        $scholarship = $this->makeScholarship([
            'nama'    => 'Beasiswa Tidak Jadi Hapus',
            'jenjang' => 'S1',
            'status'  => 'draft',
        ]);

        $this->runCase(
            'PBI #12',
            'TC.HapusBeasiswa.002',
            'Batalkan hapus: klik Batal (dismissDialog) pada dialog confirm, data tetap ada di daftar',
            'Negative',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships')
                            ->assertSee('Beasiswa Tidak Jadi Hapus');

                    // Klik tombol hapus pada baris data
                    $browser->press('Hapus')
                            ->waitForDialog();

                    // EXTRA: dismissDialog() – membatalkan dialog konfirmasi hapus Chrome
                    $browser->dismissDialog();

                    // Pause singkat untuk memastikan browser tidak melakukan navigasi
                    $browser->pause(500);

                    // Pastikan data tetap berada di halaman daftar
                    $browser->assertPathIs('/admin/scholarships')
                            ->assertSee('Beasiswa Tidak Jadi Hapus');

                    // EXTRA: query DB – memverifikasi deleted_at tetap null dan data tidak terhapus
                    $this->assertDatabaseHas('beasiswas', [
                        'id'         => $scholarship->id,
                        'deleted_at' => null,
                    ]);

                    $this->shot($browser, 'PBI12_TC.HapusBeasiswa.002');
                });
            }
        );
    }
}
