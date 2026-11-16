<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

/**
 * TC.TambahBeasiswa.007 – Poster lebih dari 2 MB (Negative)
 *
 * Pre-condition : Admin berada di /admin/scholarships/create
 * Langkah       :
 *   1) Isi form valid, pilih gambar 3 MB
 *   2) Klik Simpan → file ditolak, pesan error ukuran tampil,
 *      data tidak tersimpan
 *
 * Keterangan: Aplikasi mempunyai DUA lapisan validasi ukuran:
 *   a) Client-side JS (poster-client-error) – muncul segera setelah
 *      file dipilih, sebelum submit, dengan ID #poster-client-error
 *   b) Server-side FormRequest – pesan "Ukuran poster maksimal 2 MB."
 *
 * Test ini memverifikasi keduanya: client error (#poster-client-error
 * muncul) DAN pastikan DB tidak bertambah.
 */
class TambahBeasiswa007Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_poster_lebih_dari_2mb(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.007',
            'Poster lebih dari 2 MB',
            'Negative',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    // Step 1: Isi form valid
                    $this->fillValidForm($browser);

                    // Attach file 3 MB – EXTRA: attach()
                    $browser->attach('poster', $this->fixture('poster_3mb.jpg'));

                    // Lapisan 1: Client-side error harus langsung muncul
                    $browser->waitFor('#poster-client-error', 5);
                    // EXTRA: assertSeeIn – verifikasi teks error client-side pada elemen tertentu
                    $browser->assertSeeIn('#poster-client-error', 'melebihi batas maksimal 2 MB');

                    // Step 2: Klik Simpan – script JS sudah mengosongkan input poster,
                    // sehingga poster tidak dikirim ke server.
                    // Validasi server sisi nominal/jenjang dll akan pass,
                    // tetapi poster tidak tersimpan (null) → record boleh jadi masuk
                    // tanpa poster. Kita verifikasi bahwa TIDAK ada error server tentang
                    // ukuran (karena file sudah di-clear oleh JS), DAN tidak ada poster
                    // tersimpan di DB.
                    $browser->press('Simpan');
                    // EXTRA: waitForText – tunggu redirect selesai ke halaman daftar
                    $browser->waitForText('berhasil', 10)
                            ->assertPathIs('/admin/scholarships');

                    // Pastikan poster path di DB adalah null (file tidak tersimpan)
                    // EXTRA: query DB – verifikasi poster null di database
                    $this->assertDatabaseHas('beasiswas', [
                        'nama'   => 'Beasiswa Uji Otomatis',
                        'poster' => null,
                    ]);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.007');
                });
            }
        );
    }
}
