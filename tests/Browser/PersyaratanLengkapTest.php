<?php

namespace Tests\Browser;

use App\Models\RequiredDocument;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PersyaratanLengkapTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_persyaratan_dokumen_dan_periode_tampil(): void
    {
        $s = Scholarship::forceCreate([
            'name' => 'Beasiswa Uji', 'organizer_name' => 'Yayasan Uji',
            'description' => 'Deskripsi uji.', 'level' => 'S1', 'status' => 'published',
            'start_date' => today()->subDays(3), 'end_date' => today()->addDays(30),
        ]);
        ScholarshipRequirement::create(['scholarship_id' => $s->id, 'min_gpa' => 3.25, 'min_semester' => 3]);
        RequiredDocument::create([
            'scholarship_id' => $s->id, 'document_name' => 'KTP',
            'file_format' => 'PDF', 'is_mandatory' => true,
        ]);

        $this->browse(function (Browser $browser) use ($s) {
            $browser->visit("/scholarships/{$s->id}")
                ->assertSee('IPK minimum: 3.25')
                ->assertSee('Semester minimum: 3')
                ->assertSeeIn('ul.list-unstyled', 'KTP')
                ->assertSeeIn('ul.list-unstyled', 'Wajib')
                ->assertSee('Dibuka')
                ->screenshot('TC.Persyaratan.001');
        });
    }
}