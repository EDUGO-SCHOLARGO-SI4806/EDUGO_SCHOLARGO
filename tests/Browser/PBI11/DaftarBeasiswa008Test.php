<?php

namespace Tests\Browser\PBI11;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class DaftarBeasiswa008Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        $scholarship = $this->makeScholarship(['nama' => 'Beasiswa Akan Dihapus']);

        $this->runCase(
            'PBI #11',
            'TC.DaftarBeasiswa.008',
            'Hapus beasiswa lewat UI (dialog Chrome) — soft delete, pindah ke arsip beasiswa',
            'Negative',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships')
                            ->assertSee('Beasiswa Akan Dihapus');

                    // Klik tombol Hapus pada baris data, tunggu dialog confirm bawaan Chrome, lalu klik OK
                    $browser->press('Hapus')
                            ->waitForDialog()
                            ->acceptDialog();

                    // Tunggu pesan sukses hapus (soft delete)
                    // EXTRA: waitForText – menunggu redirect selesai dan pesan flash sukses tampil
                    $browser->waitForText('berhasil dipindahkan ke arsip beasiswa', 10)
                            ->assertPathIs('/admin/scholarships');

                    // Pastikan beasiswa sudah tidak ada di tabel daftar aktif
                    $browser->assertDontSeeIn('tbody', 'Beasiswa Akan Dihapus');

                    // Buka halaman Arsip Beasiswa (/admin/scholarships/trashed)
                    $browser->clickLink('Arsip Beasiswa')
                            ->assertPathIs('/admin/scholarships/trashed')
                            ->assertSee('Beasiswa Akan Dihapus');

                    $this->shot($browser, 'PBI11_TC.DaftarBeasiswa.008');
                });

                // Verifikasi record ter-soft-delete dan kolom deleted_at terisi di database
                // EXTRA: query DB – memverifikasi soft delete pada tabel beasiswas
                $this->assertSoftDeleted('beasiswas', ['id' => $scholarship->id]);
            }
        );
    }
}
