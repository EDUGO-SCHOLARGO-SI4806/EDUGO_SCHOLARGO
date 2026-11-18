<?php

namespace Tests\Browser;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BadgeIpkTest extends DuskTestCase
{
    public function testShow04BadgeIpk(): void
    {
        $user = User::first();

        $this->browse(function (Browser $browser) use ($user) {
            StudentProfile::where('user_id', $user->id)->update(['ipk' => 3.50]);
            $browser->visit('/profile')
                ->assertPresent('.sg-badge-success')
                ->assertSeeIn('.sg-badge-success', '3.50');

            StudentProfile::where('user_id', $user->id)->update(['ipk' => 3.49]);
            $browser->visit('/profile')
                ->assertPresent('.sg-badge-info')
                ->assertSeeIn('.sg-badge-info', '3.49');

            StudentProfile::where('user_id', $user->id)->update(['ipk' => 3.00]);
            $browser->visit('/profile')
                ->assertPresent('.sg-badge-info')
                ->assertSeeIn('.sg-badge-info', '3.00');

            StudentProfile::where('user_id', $user->id)->update(['ipk' => 2.99]);
            $browser->visit('/profile')
                ->assertPresent('.sg-badge-warning')
                ->assertSeeIn('.sg-badge-warning', '2.99');
        });
    }
}