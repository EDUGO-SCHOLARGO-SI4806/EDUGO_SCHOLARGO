<?php

namespace Tests\Browser\PBI13;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class KriteriaIpk001Test extends DuskTestCase
{
    use RecordsResult;

    public function test_kriteria_ipk_valid(): void
    {
        $this->runCase(
            'PBI #13',
            'TC.KriteriaIPK.001',
            'Simpan kriteria IPK dan semester valid',
            'Positive',
            function () {
                $this->browse(function (Browser $browser) {
                    $browser->visit('/admin/requirements/1/edit')
                            ->assertSee('Atur Kriteria Kualifikasi')
                            ->clear('min_gpa')
                            ->type('min_gpa', '3.50')
                            ->clear('min_semester')
                            ->type('min_semester', '3')
                            ->press('Simpan Kriteria')
                            ->waitForText('berhasil', 5)
                            ->assertPathIs('/admin/requirements');

                    $this->shot($browser, 'PBI13_TC.KriteriaIPK.001');
                });
            }
        );
    }
}
