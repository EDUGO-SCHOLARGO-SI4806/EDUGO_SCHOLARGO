<?php

namespace Tests\Browser;

use App\Models\Scholarship;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TampilDetailTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_detail_beasiswa_tampil(): void
    {
        $s = Scholarship::forceCreate([
            'name' => 'Beasiswa Uji', 'organizer_name' => 'Yayasan Uji',
            'description' => 'Deskripsi uji.', 'level' => 'S1', 'status' => 'published',
        ]);

        $this->browse(function (Browser $browser) use ($s) {
            $browser->visit("/scholarships/{$s->id}")
                ->assertSee('Beasiswa Uji')
                ->assertSee('Penyelenggara: Yayasan Uji')
                ->assertSee('Deskripsi uji.')
                ->screenshot('TC.DetailBeasiswa.001');
        });
    }
}