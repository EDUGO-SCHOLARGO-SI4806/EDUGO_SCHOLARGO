<?php

namespace Tests\Browser;

use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProfilTidakMemenuhiTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_profil_tidak_memenuhi_syarat(): void
    {
        $s = Scholarship::forceCreate([
            'name' => 'Beasiswa Uji', 'organizer_name' => 'Yayasan Uji',
            'description' => 'Deskripsi uji.', 'level' => 'S1', 'status' => 'published',
        ]);
        ScholarshipRequirement::create(['scholarship_id' => $s->id, 'min_gpa' => 3.25, 'min_semester' => 3]);

        $user = User::forceCreate(['name' => 'Mahasiswa Uji', 'email' => 'mhs@example.com', 'password' => bcrypt('password')]);
        StudentProfile::forceCreate(['user_id' => $user->id, 'ipk' => 2.80, 'semester' => 2]);

        $this->browse(function (Browser $browser) use ($s, $user) {
            $browser->loginAs($user)
                ->visit("/scholarships/{$s->id}")
                ->assertSee('Profilmu belum memenuhi syarat beasiswa ini.')
                ->screenshot('TC.CekKesesuaian.002');
        });
    }
}