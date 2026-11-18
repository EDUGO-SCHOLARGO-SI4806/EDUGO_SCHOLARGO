<?php

namespace Tests\Browser\PBI14;

use Laravel\Dusk\Browser;
use Tests\Browser\Support\RecordsResult;
use Tests\DuskTestCase;

class DokumenWajib001Test extends DuskTestCase
{
    use RecordsResult;

    public function test_tambah_dokumen_wajib(): void
    {
        $this->runCase(
            'PBI #14',
            'TC.DokumenWajib.001',
            'Memilih daftar dokumen persyaratan wajib standar',
            'Positive',
            function () {
                $this->browse(function (Browser $browser) {
                    $docName = 'Transkrip Nilai Legalitas ' . time();

                    $browser->visit('/admin/requirements/1/documents')
                            ->assertSee('Menentukan Daftar Dokumen Wajib')
                            ->type('document_name', $docName)
                            ->type('description', 'Wajib dilegalisir basah atau bertanda tangan digital.')
                            ->select('file_format', 'PDF')
                            ->select('is_mandatory', '1')
                            ->press('Tambahkan Dokumen')
                            ->waitForText('Berhasil', 5)
                            ->assertSee($docName)
                            ->assertSee('Wajib');

                    $this->shot($browser, 'PBI14_TC.DokumenWajib.001');
                });
            }
        );
    }
}
