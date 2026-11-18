<?php

namespace Tests\Browser\PBI12;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class UbahBeasiswa006Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        // Siapkan poster lama di disk public
        // EXTRA: storage – menyiapkan file poster lama di disk public
        $oldPoster = 'posters/old_poster_006.jpg';
        Storage::disk('public')->put($oldPoster, file_get_contents($this->fixture('poster_1mb.jpg')));

        $scholarship = $this->makeScholarship([
            'nama'    => 'Beasiswa Poster Error',
            'jenjang' => 'S1',
            'status'  => 'draft',
            'poster'  => $oldPoster,
        ]);

        $this->runCase(
            'PBI #12',
            'TC.UbahBeasiswa.006',
            'Poster tidak valid: format PDF dan ukuran 3 MB, poster lama tetap tersimpan',
            'Negative',
            function () use ($admin, $scholarship, $oldPoster) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship, $oldPoster) {

                    // ── Skenario 1: Poster format PDF ─────────────────────
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/' . $scholarship->id . '/edit')
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id . '/edit');

                    // EXTRA: attach() – mengunggah file PDF yang tidak valid
                    $browser->attach('poster', $this->fixture('dokumen.pdf'));
                    $browser->press('Simpan Perubahan');

                    // EXTRA: waitForText – menunggu respon server dan pesan error format tampil
                    $browser->waitForText('Format poster harus JPEG, PNG, JPG, atau WEBP.', 10)
                            ->assertSee('Format poster harus JPEG, PNG, JPG, atau WEBP.');

                    // ── Skenario 2: Poster ukuran 3 MB ────────────────────
                    $browser->visit('/admin/scholarships/' . $scholarship->id . '/edit');

                    // EXTRA: attach() – mengunggah file gambar lebih dari 2 MB
                    $browser->attach('poster', $this->fixture('poster_3mb.jpg'));

                    // Client-side JS menampilkan alert error ukuran sebelum submit
                    $browser->waitFor('#poster-client-error', 5);
                    // EXTRA: assertSeeIn – memverifikasi pesan error ukuran file client-side
                    $browser->assertSeeIn('#poster-client-error', 'melebihi batas maksimal 2 MB');

                    // Submit form edit
                    $browser->press('Simpan Perubahan');

                    // EXTRA: storage – memverifikasi file poster lama tetap ada di disk storage
                    $this->assertTrue(Storage::disk('public')->exists($oldPoster));

                    // EXTRA: query DB – memverifikasi kolom poster di database tidak berubah
                    $fresh = $scholarship->fresh();
                    $this->assertEquals($oldPoster, $fresh->poster);

                    $this->shot($browser, 'PBI12_TC.UbahBeasiswa.006');
                });
            }
        );
    }
}
