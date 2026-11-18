<?php

namespace Tests\Browser;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LihatProfilSebagianTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', ['--seed' => true]);
    }
    public function testShow03Sebagian(): void
    {
        $user = User::first();
        StudentProfile::where('user_id', $user->id)->update([
            'nim' => null,
            'phone' => null,
            'tanggal_lahir' => null,
            'alamat' => null,
            'jenjang' => null,
            'ipk' => 3.20,
            'semester' => 3,
            'program_studi' => 'Sistem Informasi',
            'kampus' => 'Telkom University',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/profile')
                ->assertSee('3.20')
                ->assertSee('Semester 3')
                ->assertSee('Sistem Informasi')
                ->assertSee('Telkom University');

            $this->assertCount(5, $browser->elements('.profile-info-value.empty'));

            $browser->assertSee('44%')
                ->assertAttribute('.progress-bar', 'aria-valuenow', '44')
                ->assertPresent('.sg-badge-info')
                ->assertSeeIn('.sg-badge-info', '3.20');
        });
    }
}