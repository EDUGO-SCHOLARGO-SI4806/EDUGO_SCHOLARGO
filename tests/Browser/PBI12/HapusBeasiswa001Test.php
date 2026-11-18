<?php

namespace Tests\Browser\PBI12;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class HapusBeasiswa001Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        $scholarship = $this->makeScholarship([
            'nama'    => 'Beasiswa Akan Diarsip',
            'jenjang' => 'S1',
            'status'  => 'draft',
        ]);

        $this->runCase(
            'PBI #12',
            'TC.HapusBeasiswa.001',
            'Hapus via UI: dialog Chrome tampil, klik OK, pesan sukses tampil, data hilang dari daftar, muncul di arsip',
            'Positive',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/' . $scholarship->id)
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id);

                    // Klik tombol Hapus pada halaman detail (memanggil confirmDeleteShow())
                    $browser->click('button.btn-danger')
                            ->waitForDialog()
                            ->acceptDialog();

                    // Tunggu pesan sukses dan verifikasi redirect ke halaman daftar
                    // EXTRA: waitForText – menunggu redirect dan pesan sukses hapus tampil
                    $browser->waitForText('berhasil dipindahkan ke arsip beasiswa', 10)
                            ->assertPathIs('/admin/scholarships');

                    // Pastikan data hilang dari tabel daftar aktif
                    $browser->assertDontSeeIn('tbody', 'Beasiswa Akan Diarsip');

                    // Pastikan data muncul di halaman arsip beasiswa
                    $browser->visit('/admin/scholarships/trashed')
                            ->assertPathIs('/admin/scholarships/trashed')
                            ->assertSee('Beasiswa Akan Diarsip');

                    // EXTRA: query DB – memverifikasi soft delete dan deleted_at terisi di database
                    $this->assertSoftDeleted('beasiswas', ['id' => $scholarship->id]);

                    $this->shot($browser, 'PBI12_TC.HapusBeasiswa.001');
                });
            }
        );
    }
}
