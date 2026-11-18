<?php

namespace Tests\Browser;

use App\Models\Scholarship;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DraftDetailTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_beasiswa_draft_404(): void
    {
        $s = Scholarship::forceCreate([
            'name' => 'Beasiswa Draft', 'organizer_name' => 'Yayasan Uji',
            'description' => 'Masih draft.', 'level' => 'S1', 'status' => 'draft',
        ]);

        $this->browse(function (Browser $browser) use ($s) {
            $browser->visit("/scholarships/{$s->id}")
                ->assertTitleContains('Not Found');
        });
    }
}