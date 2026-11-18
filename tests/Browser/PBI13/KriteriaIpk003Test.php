<?php

namespace Tests\Browser\PBI13;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class KriteriaIpk003Test extends DuskTestCase
{
    use RecordsResult;

    public function test_kriteria_ipk_negatif(): void
    {
        $this->runCase(
            'PBI #13',
            'TC.KriteriaIPK.003',
            'Input nilai IPK angka negatif atau karakter tidak valid',
            'Negative',
            function () {
                $this->browse(function (Browser $browser) {
                    $browser->visit('/admin/requirements/1/edit')
                            ->clear('min_gpa')
                            ->type('min_gpa', '-1.00')
                            ->press('Simpan Kriteria')
                            ->assertSee('0.00 dan 4.00');

                    $this->shot($browser, 'PBI13_TC.KriteriaIPK.003');
                });
            }
        );
    }
}
