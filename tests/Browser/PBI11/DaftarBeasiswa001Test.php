<?php

namespace Tests\Browser\PBI11;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class DaftarBeasiswa001Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        // Seed beberapa data dengan created_at berbeda untuk verifikasi urutan (terbaru di atas)
        $this->makeScholarship([
            'nama'          => 'Beasiswa Terlama',
            'penyelenggara' => 'Yayasan A',
            'jenjang'       => 'D3',
            'tanggal_tutup' => '2026-10-01',
            'status'        => 'draft',
            'created_at'    => now()->subDays(2),
        ]);

        $this->makeScholarship([
            'nama'          => 'Beasiswa Sedang',
            'penyelenggara' => 'Yayasan B',
            'jenjang'       => 'S1',
            'tanggal_tutup' => '2026-11-01',
            'status'        => 'draft',
            'created_at'    => now()->subDay(),
        ]);

        $this->makeScholarship([
            'nama'          => 'Beasiswa Terbaru',
            'penyelenggara' => 'Yayasan C',
            'jenjang'       => 'S2',
            'tanggal_tutup' => '2026-12-01',
            'status'        => 'published',
            'created_at'    => now(),
        ]);

        $this->runCase(
            'PBI #11',
            'TC.DaftarBeasiswa.001',
            'Lihat daftar beasiswa (ada data, tabel tampil, urutan terbaru di atas)',
            'Positive',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships');

                    // Verifikasi tabel dan kolom header tampil
                    $browser->assertSee('Nama Beasiswa', true)
                            ->assertSee('Penyelenggara', true)
                            ->assertSee('Kategori / Jenjang', true)
                            ->assertSee('Periode', true)
                            ->assertSee('Status', true);

                    // Verifikasi data tabel tampil sesuai field
                    $browser->assertSee('Beasiswa Terbaru')
                            ->assertSee('Yayasan C')
                            ->assertSee('S2')
                            ->assertSee('Published')
                            ->assertSee('Beasiswa Terlama');

                    // Verifikasi urutan: data terbaru di atas
                    // EXTRA: assertSeeIn – memeriksa urutan baris tabel (baris 1 data terbaru)
                    $browser->assertSeeIn('tbody tr:nth-child(1)', 'Beasiswa Terbaru')
                            ->assertSeeIn('tbody tr:nth-child(2)', 'Beasiswa Sedang')
                            ->assertSeeIn('tbody tr:nth-child(3)', 'Beasiswa Terlama');

                    $this->shot($browser, 'PBI11_TC.DaftarBeasiswa.001');
                });
            }
        );
    }
}
