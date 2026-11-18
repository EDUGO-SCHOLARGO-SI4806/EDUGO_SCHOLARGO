<?php

namespace Tests\Browser;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LihatProfilKosongTest extends DuskTestCase
{
    public function testShow02Kosong(): void
    {
        $user = User::first();
        StudentProfile::where('user_id', $user->id)->delete();

        $this->browse(function (Browser $browser) {
            $browser->visit('/profile')
                ->assertTitle('Profil Saya - ScholarGo');

            $this->assertCount(9, $browser->elements('.profile-info-value.empty'));

            $browser->assertSee('Belum diisi')
                ->assertAttribute('.progress-bar', 'aria-valuenow', '0')
                ->assertSee('0%')
                ->assertSee('Belum ada dokumen yang diunggah')
                ->assertSee('Upload Dokumen Pertama');
        });
    }
}