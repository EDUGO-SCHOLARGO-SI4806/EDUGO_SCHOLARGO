<?php

namespace Tests\Browser\PBI15;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class PeriodePendaftaran003Test extends DuskTestCase
{
    use RecordsResult;

    public function test_periode_tanggal_kosong(): void
    {
        $this->runCase(
            'PBI #15',
            'TC.PeriodePendaftaran.003',
            'Submit form periode dengan mengosongkan tanggal pendaftaran',
            'Negative',
            function () {
                $this->browse(function (Browser $browser) {
                    $browser->visit('/admin/requirements/1/period')
                            ->script([
                                "document.querySelector('[name=open_date]').value=''",
                                "document.querySelector('[name=close_date]').value=''",
                            ]);

                    $browser->press('Simpan Periode Pendaftaran')
                            ->assertSee('wajib diisi');

                    $this->shot($browser, 'PBI15_TC.PeriodePendaftaran.003');
                });
            }
        );
    }
}
