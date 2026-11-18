<?php

namespace Tests\Browser\PBI12;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\Browser\Support\ScholarshipHelpers;
use Tests\DuskTestCase;

class HapusBeasiswa003Test extends DuskTestCase
{
    use DatabaseMigrations, ScholarshipHelpers, RecordsResult;

    public function testCase(): void
    {
        $admin = $this->makeAdmin();
        $scholarship = $this->makeScholarship([
            'nama'    => 'Beasiswa Sudah Dihapus',
            'jenjang' => 'S1',
            'status'  => 'draft',
        ]);

        $this->runCase(
            'PBI #12',
            'TC.HapusBeasiswa.003',
            'Akses detail beasiswa yang sudah di-soft-delete — halaman 404 tampil',
            'Negative',
            function () use ($admin, $scholarship) {
                // Soft delete via model sebelum diakses via browser
                $scholarship->delete();

                $this->browse(function (Browser $browser) use ($admin, $scholarship) {
                    $browser->loginAs($admin)
                            ->visit('/admin/scholarships/' . $scholarship->id);

                    // Laravel Route Model Binding mengembalikan 404 untuk record soft-deleted
                    $browser->assertSee('404');

                    $this->shot($browser, 'PBI12_TC.HapusBeasiswa.003');
                });
            }
        );
    }
}
