<?php

namespace Tests\Browser\PBI10;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class TambahBeasiswa010Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.010',
            'Reset form',
            'Positive',
            function () use ($admin) {
                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create');

                    // Isi sebagian field form
                    $browser->type('name', 'Beasiswa Reset Test')
                            ->type('organizer_name', 'Lembaga Reset');

                    // Attach poster dan tunggu preview muncul
                    // EXTRA: attach() – upload file fixture poster
                    $browser->attach('poster', $this->fixture('poster_1mb.jpg'));
                    $browser->waitFor('#poster-preview-card', 5);

                    // Klik tombol reset
                    $browser->click('#btn-reset');

                    // Tunggu JS clearPoster() selesai dieksekusi
                    $browser->pause(500);

                    // Verifikasi field name dan organizer_name sudah dikosongkan
                    // EXTRA: assertInputValue – verifikasi field sudah dikosongkan setelah reset
                    $browser->assertInputValue('name', '')
                            ->assertInputValue('organizer_name', '');

                    // EXTRA: assertScript – poster-preview-card harus hidden setelah reset
                    // clearPoster() mengeset style.display='none' pada elemen tersebut
                    $browser->assertScript(
                        "document.getElementById('poster-preview-card').style.display === 'none'",
                        true
                    );

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.010');
                });
            }
        );
    }
}
