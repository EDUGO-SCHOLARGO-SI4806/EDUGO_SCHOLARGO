<?php

namespace Tests\Browser\PBI12;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class UbahBeasiswa005Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        $scholarship = $this->makeScholarship([
            'nama'          => 'Beasiswa Tanggal Error',
            'jenjang'       => 'S1',
            'status'        => 'draft',
            'tanggal_buka'  => '2026-01-01',
            'tanggal_tutup' => '2026-01-31',
        ]);

        $this->runCase(
            'PBI #12',
            'TC.UbahBeasiswa.005',
            'Tanggal Mulai 10/12/2026, Deadline 01/12/2026: data ditolak, pesan error tanggal tampil, data lama tidak berubah',
            'Negative',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/' . $scholarship->id . '/edit')
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id . '/edit');

                    // Set tanggal mulai 10 Des 2026, deadline 01 Des 2026 (lebih awal dari mulai)
                    // EXTRA: script – type() tidak konsisten untuk input[type=date] di semua OS
                    $browser->script("document.querySelector('[name=start_date]').value='2026-12-10'");
                    $browser->script("document.querySelector('[name=end_date]').value='2026-12-01'");

                    // Submit form edit
                    $browser->press('Simpan Perubahan');

                    // Verifikasi pesan error tanggal muncul
                    // EXTRA: waitForText – menunggu respon server dan pesan error tanggal tampil
                    $browser->waitForText('Deadline tidak boleh lebih awal dari tanggal mulai.', 10)
                            ->assertSee('Deadline tidak boleh lebih awal dari tanggal mulai.');

                    // EXTRA: query DB – memverifikasi data lama di database tidak berubah
                    $this->assertDatabaseHas('beasiswas', [
                        'id'            => $scholarship->id,
                        'nama'          => 'Beasiswa Tanggal Error',
                        'tanggal_buka'  => '2026-01-01',
                        'tanggal_tutup' => '2026-01-31',
                    ]);

                    $this->shot($browser, 'PBI12_TC.UbahBeasiswa.005');
                });
            }
        );
    }
}
