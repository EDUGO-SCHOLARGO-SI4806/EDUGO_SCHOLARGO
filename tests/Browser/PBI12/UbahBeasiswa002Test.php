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

class UbahBeasiswa002Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();

        // Siapkan poster lama di disk public
        // EXTRA: storage – menyiapkan file poster lama di disk public
        $oldPosterPath = 'posters/old_poster_002.jpg';
        Storage::disk('public')->put($oldPosterPath, file_get_contents($this->fixture('poster_1mb.jpg')));

        $scholarship = $this->makeScholarship([
            'nama'          => 'Beasiswa Ganti Poster',
            'penyelenggara' => 'Lembaga Poster',
            'jenjang'       => 'S1',
            'status'        => 'draft',
            'poster'        => $oldPosterPath,
        ]);

        $this->runCase(
            'PBI #12',
            'TC.UbahBeasiswa.002',
            'Ganti poster beasiswa: upload JPG 1 MB, pratinjau tampil, poster lama terhapus dari storage',
            'Positive',
            function () use ($admin, $scholarship, $oldPosterPath) {
                $this->browse(function (Browser $browser) use ($admin, $scholarship, $oldPosterPath) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/' . $scholarship->id . '/edit')
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id . '/edit');

                    // Pilih poster baru JPG 1 MB
                    // EXTRA: attach() – mengunggah file poster baru
                    $browser->attach('poster', $this->fixture('poster_1mb.jpg'))
                            ->waitFor('#poster-preview-card', 5);

                    // Submit form edit
                    $browser->press('Simpan Perubahan');

                    // Tunggu pesan sukses pembaruan data
                    // EXTRA: waitForText – menunggu redirect dan pesan sukses tampil
                    $browser->waitForText('Data beasiswa berhasil diperbarui.', 10)
                            ->assertPathIs('/admin/scholarships/' . $scholarship->id);

                    // Verifikasi elemen gambar poster baru tampil di halaman detail
                    // EXTRA: assertPresent – memastikan elemen gambar poster dirender pada view detail
                    $browser->assertPresent('.poster-img');

                    // EXTRA: storage – memverifikasi file poster lama sudah terhapus dari disk public
                    $this->assertFalse(Storage::disk('public')->exists($oldPosterPath));

                    // EXTRA: query DB & storage – memverifikasi path poster baru tersimpan dan filenya ada di storage
                    $fresh = $scholarship->fresh();
                    $this->assertNotNull($fresh->poster);
                    $this->assertNotEquals($oldPosterPath, $fresh->poster);
                    $this->assertTrue(Storage::disk('public')->exists($fresh->poster));

                    $this->shot($browser, 'PBI12_TC.UbahBeasiswa.002');
                });
            }
        );
    }
}
