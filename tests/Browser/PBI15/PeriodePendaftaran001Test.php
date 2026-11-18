<?php

namespace Tests\Browser\PBI15;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class PeriodePendaftaran001Test extends DuskTestCase
{
    use RecordsResult;

    public function test_periode_pendaftaran_valid(): void
    {
        $this->runCase(
            'PBI #15',
            'TC.PeriodePendaftaran.001',
            'Menentukan rentang tanggal periode pendaftaran valid',
            'Positive',
            function () {
                $this->browse(function (Browser $browser) {
                    $open  = now()->format('Y-m-d');
                    $close = now()->addDays(30)->format('Y-m-d');

                    $browser->visit('/admin/requirements/1/period')
                            ->assertSee('Periode')
                            ->script([
                                "document.querySelector('[name=open_date]').value='$open'",
                                "document.querySelector('[name=close_date]').value='$close'",
                            ]);

                    $browser->press('Simpan Periode Pendaftaran')
                            ->waitForText('berhasil', 5)
                            ->assertSee('berhasil');

                    $this->shot($browser, 'PBI15_TC.PeriodePendaftaran.001');
                });
            }
        );
    }
}
