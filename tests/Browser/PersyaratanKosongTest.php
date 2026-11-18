<?php

namespace Tests\Browser;

use App\Models\Scholarship;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PersyaratanKosongTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tanpa_syarat_tanpa_dokumen_dan_periode_ditutup(): void
    {
        $s = Scholarship::forceCreate([
            'name' => 'Beasiswa Uji', 'organizer_name' => 'Yayasan Uji',
            'description' => 'Deskripsi uji.', 'level' => 'S1', 'status' => 'published',
            'start_date' => today()->subDays(40), 'end_date' => today()->subDays(5),
        ]);

        $this->browse(function (Browser $browser) use ($s) {
            $browser->visit("/scholarships/{$s->id}")
                ->assertSee('Belum ada persyaratan kualifikasi.')
                ->assertSee('Belum ada daftar dokumen.')
                ->assertSee('Ditutup')
                ->screenshot('TC.Persyaratan.002');
        });
    }
}