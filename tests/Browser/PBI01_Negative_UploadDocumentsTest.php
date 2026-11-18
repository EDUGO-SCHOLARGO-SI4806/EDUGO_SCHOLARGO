<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI01_Negative_UploadDocumentsTest extends DuskTestCase
{
    /**
     * Student cannot access organizer upload page.
     */
    public function test_student_cannot_access_organizer_upload_page(): void
    {
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                ->visit(route('organizer.documents.create'))
                ->assertSee('HALAMAN INI KHUSUS PENYELENGGARA BEASISWA.');
        });
    }
}
