<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

/**
 * TC.TambahBeasiswa.006 – Poster format salah (PDF) (Negative)
 *
 * Pre-condition : Admin berada di /admin/scholarships/create
 * Langkah       :
 *   1) Isi form valid, pilih file PDF sebagai poster
 *   2) Klik Simpan → file ditolak, pesan error format tampil,
 *      data tidak tersimpan
 *
 * Catatan: Aplikasi memeriksa format di sisi client (JS, via accept
 * attribute) DAN sisi server (mimes:jpeg,png,jpg,webp). Karena form
 * novalidate, server akan menerima request dan menolak di FormRequest.
 * Pesan error server: "Format poster harus JPEG, PNG, JPG, atau WEBP."
 */
class TambahBeasiswa006Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_poster_format_salah(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.006',
            'Poster format salah (PDF)',
            'Negative',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    // Step 1: Isi form valid
                    $this->fillValidForm($browser);

                    // Attach PDF – EXTRA: attach() satu-satunya cara upload
                    // Browser mungkin mengabaikan accept attr; server yang menolak
                    $browser->attach('poster', $this->fixture('dokumen.pdf'));

                    // Step 2: Klik Simpan
                    $browser->press('Simpan');

                    // Tunggu pesan error format dari FormRequest messages()
                    // EXTRA: waitForText – tunggu pesan error format dari FormRequest
                    $browser->waitForText('Format poster harus JPEG, PNG, JPG, atau WEBP.', 10)
                            ->assertPathIs('/admin/scholarships/create');

                    // DB tidak bertambah
                    // EXTRA: query DB – verifikasi jumlah data di database tidak bertambah
                    $this->assertDatabaseCount('beasiswas', 0);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.006');
                });
            }
        );
    }
}
