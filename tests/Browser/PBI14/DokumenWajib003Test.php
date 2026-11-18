<?php

namespace Tests\Browser\PBI14;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class DokumenWajib003Test extends DuskTestCase
{
    use RecordsResult;

    public function test_dokumen_nama_kosong(): void
    {
        $this->runCase(
            'PBI #14',
            'TC.DokumenWajib.003',
            'Submit form tanpa mengisi nama dokumen persyaratan',
            'Negative',
            function () {
                $this->browse(function (Browser $browser) {
                    $browser->visit('/admin/requirements/1/documents')
                            ->clear('document_name')
                            ->press('Tambahkan Dokumen')
                            ->assertSee('wajib diisi');

                    $this->shot($browser, 'PBI14_TC.DokumenWajib.003');
                });
            }
        );
    }
}
