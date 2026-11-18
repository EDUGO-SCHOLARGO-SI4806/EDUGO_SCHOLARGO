<?php

namespace Tests\Browser\PBI11;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class DaftarBeasiswa002Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();


        for ($i = 1; $i <= 12; $i++) {
            $num = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $this->makeScholarship([
                'nama'       => "Beasiswa Uji {$num}",
                'created_at' => now()->subMinutes(13 - $i),
            ]);
        }

        $this->runCase(
            'PBI #11',
            'TC.DaftarBeasiswa.002',
            'Pagination — seed 12 data, tampil 10 per halaman, navigasi muncul, klik halaman 2',
            'Positive',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships')
                            ->assertPathIs('/admin/scholarships');

                    // Halaman 1: tampil 10 data terbaru (Beasiswa Uji 12 s.d. 03) dan tidak tampil data halaman 2
                    $browser->assertSee('Beasiswa Uji 12')
                            ->assertSee('Beasiswa Uji 03')
                            ->assertDontSee('Beasiswa Uji 02')
                            ->assertDontSee('Beasiswa Uji 01');

                    // Verifikasi navigasi pagination muncul
                    // EXTRA: assertPresent – memastikan kontainer navigasi pagination tampil
                    $browser->assertPresent('.pagination-container');

                    // Klik navigasi ke halaman 2
                    $browser->click('a[aria-label="Go to page 2"]');

                    // Pada halaman 2: data berikutnya tampil (Beasiswa Uji 02 dan 01), data halaman 1 tidak tampil
                    $browser->assertSee('Beasiswa Uji 02')
                            ->assertSee('Beasiswa Uji 01')
                            ->assertDontSee('Beasiswa Uji 12');

                    $this->shot($browser, 'PBI11_TC.DaftarBeasiswa.002');
                });
            }
        );
    }
}
