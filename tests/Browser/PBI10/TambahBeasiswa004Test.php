<?php

namespace Tests\Browser\PBI10;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

/**
 * TC.TambahBeasiswa.004 – Field wajib kosong (Negative)
 *
 * Pre-condition : Admin berada di /admin/scholarships/create
 * Langkah       :
 *   1) Biarkan semua field wajib kosong (form default kosong)
 *   2) Klik Simpan → data TIDAK tersimpan, pesan error tampil
 *
 * Catatan: form memakai atribut novalidate, sehingga validasi HTML5
 * dimatikan dan server validation yang aktif.
 */
class TambahBeasiswa004Test extends DuskTestCase
{
    use DatabaseMigrations, RecordsResult, ScholarshipHelpers;

    public function test_field_wajib_kosong(): void
    {
        $this->runCase(
            'PBI #10',
            'TC.TambahBeasiswa.004',
            'Field wajib kosong',
            'Negative',
            function () {
                $admin = $this->makeAdmin();

                $this->browse(function (Browser $browser) use ($admin) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/create')
                            ->assertPathIs('/admin/scholarships/create');

                    // Step 1: Tidak mengisi apapun, langsung klik Simpan
                    $browser->press('Simpan');

                    // Step 2: Tunggu hingga error validasi tampil setelah redirect kembali
                    $browser->waitFor('.alert-error', 10)
                            ->assertPathIs('/admin/scholarships/create');

                    // Pesan error validasi harus tampil (blok .alert-error)
                    $browser->assertSee('Terdapat kesalahan pada form');

                    // Error spesifik per field wajib
                    // Alasan ekspektasi test: "Status wajib dipilih." TIDAK muncul karena
                    // <select name="status"> pada create.blade.php memiliki nilai awal default 'draft'
                    // (@selected(old('status', 'draft') === 'draft')), sehingga nilai status selalu terkirim
                    // dan lolos validasi server. Kegagalan sebelumnya adalah bug script test yang berekspektasi
                    // pesan error untuk status yang sebenarnya memiliki nilai default.
                    $browser->assertSee('Nama beasiswa wajib diisi.')
                            ->assertSee('Penyelenggara wajib diisi.')
                            ->assertSee('Deskripsi wajib diisi.')
                            ->assertSee('Jenjang wajib dipilih.');

                    // Verifikasi DB tidak bertambah
                    // EXTRA: query DB – verifikasi jumlah data di tabel beasiswas tetap 0
                    $this->assertDatabaseCount('beasiswas', 0);

                    $this->shot($browser, 'PBI10_TC.TambahBeasiswa.004');
                });
            }
        );
    }
}
