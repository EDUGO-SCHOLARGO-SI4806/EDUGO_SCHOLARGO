<?php

namespace Tests\Browser\PBI11;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class DaftarBeasiswa003Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();


        Storage::disk('public')->put('posters/poster_lengkap.jpg', 'fake-image-data');

        $scholarship = $this->makeScholarship([
            'nama'          => 'Beasiswa Unggulan Prestasi',
            'penyelenggara' => 'Kementerian Pendidikan',
            'deskripsi'     => 'Deskripsi lengkap beasiswa unggulan prestasi untuk pengujian.',
            'jenjang'       => 'S1',
            'kategori'      => 'Akademik',
            'nominal'       => 15000000,
            'kuota'         => 100,
            'tanggal_buka'  => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'link_sumber'   => 'https://example.com/beasiswa-unggulan',
            'poster'        => 'posters/poster_lengkap.jpg',
            'status'        => 'published',
        ]);

        $this->runCase(
            'PBI #11',
            'TC.DaftarBeasiswa.003',
            'Detail data lengkap — semua field sesuai data, poster tampil, link sumber bisa diklik',
            'Positive',
            function () use ($admin, $scholarship) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships')
                            ->assertSee('Beasiswa Unggulan Prestasi');

                    // Klik tombol Detail pada halaman daftar
                    $browser->clickLink('Detail');

                    // Verifikasi halaman detail terbuka
                    $browser->assertPathIs('/admin/scholarships/' . $scholarship->id);

                    // Verifikasi semua field sesuai data
                    $browser->assertSee('Beasiswa Unggulan Prestasi')
                            ->assertSee('Kementerian Pendidikan')
                            ->assertSee('Published')
                            ->assertSee('S1')
                            ->assertSee('Akademik')
                            ->assertSee('Rp 15.000.000')
                            ->assertSee('100 orang')
                            ->assertSee('Deskripsi lengkap beasiswa unggulan prestasi untuk pengujian.')
                            ->assertSee('Buka Halaman Resmi Beasiswa');

                    // Verifikasi poster tampil
                    // EXTRA: assertPresent – memastikan elemen gambar poster dirender pada view detail
                    $browser->assertPresent('.poster-img');

                    // Verifikasi link sumber ada dan dapat diklik
                    // EXTRA: assertPresent – memastikan tautan sumber resmi mengarah ke URL eksternal yang benar
                    $browser->assertPresent('a.btn-source[href="https://example.com/beasiswa-unggulan"]');

                    $this->shot($browser, 'PBI11_TC.DaftarBeasiswa.003');
                });
            }
        );
    }
}
