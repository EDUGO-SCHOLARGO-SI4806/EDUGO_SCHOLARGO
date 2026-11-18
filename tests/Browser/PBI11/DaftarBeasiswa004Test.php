<?php

namespace Tests\Browser\PBI11;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class DaftarBeasiswa004Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        $scholarship = $this->makeScholarship([
            'nama'          => 'Beasiswa Minimal Mandiri',
            'penyelenggara' => 'Yayasan Peduli',
            'deskripsi'     => 'Deskripsi beasiswa minimal hanya field wajib.',
            'jenjang'       => 'D3',
            'status'        => 'draft',
            'kategori'      => null,
            'nominal'       => null,
            'kuota'         => null,
            'tanggal_buka'  => null,
            'tanggal_tutup' => null,
            'link_sumber'   => null,
            'poster'        => null,
        ]);

        $this->runCase(
            'PBI #11',
            'TC.DaftarBeasiswa.004',
            'Detail data minimal — tanpa poster/nominal/kuota/deadline, field kosong tampil "-" atau "Tidak terbatas"',
            'Positive',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships')
                            ->assertSee('Beasiswa Minimal Mandiri');

                    // Klik tombol Detail pada halaman daftar
                    $browser->clickLink('Detail');

                    // Verifikasi halaman detail terbuka tanpa error
                    $browser->assertPathIs('/admin/scholarships/' . $scholarship->id);

                    // Verifikasi field wajib tampil
                    $browser->assertSee('Beasiswa Minimal Mandiri')
                            ->assertSee('Yayasan Peduli')
                            ->assertSee('D3')
                            ->assertSee('Draft')
                            ->assertSee('Deskripsi beasiswa minimal hanya field wajib.');

                    // Verifikasi field kosong tampil "-" atau "Tidak terbatas"
                    // Kuota kosong menampilkan "Tidak terbatas", nominal/kategori/deadline menampilkan "-"
                    $browser->assertSee('Tidak terbatas')
                            ->assertSee('-');

                    // Verifikasi poster tidak tampil jika tidak ada gambar
                    // EXTRA: assertMissing – memastikan poster tidak dirender jika data poster bernilai null
                    $browser->assertMissing('.poster-img');

                    $this->shot($browser, 'PBI11_TC.DaftarBeasiswa.004');
                });
            }
        );
    }
}
