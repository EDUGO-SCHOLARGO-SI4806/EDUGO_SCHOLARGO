<?php

namespace Tests\Browser\PBI14;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class DokumenWajib002Test extends DuskTestCase
{
    use RecordsResult;

    public function test_tambah_dokumen_kustom(): void
    {
        $this->runCase(
            'PBI #14',
            'TC.DokumenWajib.002',
            'Menambahkan nama dokumen persyaratan kustom (tambahan)',
            'Positive',
            function () {
                $this->browse(function (Browser $browser) {
                    $docName = 'Portofolio Desain ' . time();

                    $browser->visit('/admin/requirements/1/documents')
                            ->type('document_name', $docName)
                            ->type('description', 'Kumpulan karya portofolio desain.')
                            ->select('file_format', 'JPG')
                            ->select('is_mandatory', '0')
                            ->press('Tambahkan Dokumen')
                            ->waitForText('Berhasil', 5)
                            ->assertSee($docName)
                            ->assertSee('Opsional');

                    $this->shot($browser, 'PBI14_TC.DokumenWajib.002');
                });
            }
        );
    }
}
