<?php

namespace Tests\Browser\PBI15;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class PeriodePendaftaran002Test extends DuskTestCase
{
    use RecordsResult;

    public function test_periode_tanggal_terbalik(): void
    {
        $this->runCase(
            'PBI #15',
            'TC.PeriodePendaftaran.002',
            'Input tanggal selesai lebih awal dari tanggal mulai (Tanggal Terbalik)',
            'Negative',
            function () {
                $this->browse(function (Browser $browser) {
                    $open  = now()->addDays(15)->format('Y-m-d');
                    $close = now()->addDays(3)->format('Y-m-d');

                    $browser->visit('/admin/requirements/1/period')
                            ->script([
                                "document.querySelector('[name=open_date]').value='$open'",
                                "document.querySelector('[name=close_date]').value='$close'",
                            ]);

                    $browser->press('Simpan Periode Pendaftaran')
                            ->assertSee('tidak boleh lebih awal');

                    $this->shot($browser, 'PBI15_TC.PeriodePendaftaran.002');
                });
            }
        );
    }
}
