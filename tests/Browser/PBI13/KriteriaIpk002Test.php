<?php

namespace Tests\Browser\PBI13;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class KriteriaIpk002Test extends DuskTestCase
{
    use RecordsResult;

    public function test_kriteria_ipk_melebihi_batas(): void
    {
        $this->runCase(
            'PBI #13',
            'TC.KriteriaIPK.002',
            'Input nilai IPK melebihi batas maksimum (> 4.00)',
            'Negative',
            function () {
                $this->browse(function (Browser $browser) {
                    $browser->visit('/admin/requirements/1/edit')
                            ->script([
                                "document.querySelector('input[name=min_gpa]').removeAttribute('max')",
                                "document.querySelector('input[name=min_gpa]').removeAttribute('min')",
                                "document.querySelector('form').setAttribute('novalidate', '')",
                            ]);

                    $browser->clear('min_gpa')
                            ->type('min_gpa', '4.50')
                            ->press('Simpan Kriteria')
                            ->assertSee('0.00 dan 4.00');

                    $this->shot($browser, 'PBI13_TC.KriteriaIPK.002');
                });
            }
        );
    }
}
