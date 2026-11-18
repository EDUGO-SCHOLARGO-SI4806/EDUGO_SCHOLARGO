<?php

namespace Tests\Browser\Support;

use Laravel\Dusk\Browser;
use Throwable;

trait RecordsResult
{
    protected ?string $lastEvidence = null;
    protected string $currentCaseId = '';
    protected string $currentPbi = '';

    /**
     * Jalankan satu test case dan catat hasilnya ke CSV.
     *
     * @param  string    $pbi       contoh: 'PBI #10'
     * @param  string    $caseId    contoh: 'TC.TambahBeasiswa.001'
     * @param  string    $scenario  deskripsi singkat
     * @param  string    $type      'Positive' | 'Negative'
     * @param  callable  $body      closure berisi logika test
     */
    protected function runCase(
        string   $pbi,
        string   $caseId,
        string   $scenario,
        string   $type,
        callable $body
    ): void {
        $this->currentPbi    = $pbi;
        $this->currentCaseId = $caseId;
        $this->lastEvidence  = null;

        $start      = microtime(true);
        $status     = 'Pass';
        $keterangan = '-';
        $evidence   = '-';

        try {
            $body();
            $evidence = $this->lastEvidence ?? '-';
        } catch (Throwable $e) {
            $status     = 'Fail';
            // Ambil baris pertama pesan exception saja agar ringkas
            $keterangan = explode("\n", $e->getMessage())[0];
            // Cari screenshot failure terbaru milik test ini
            $evidence = $this->findLatestFailureScreenshot($caseId);
            throw $e;    // lempar ulang agar PHPUnit melaporkan kegagalan
        } finally {
            $elapsed = round(microtime(true) - $start, 2) . ' s';
            $this->writeToCsv($pbi, $caseId, $scenario, $type, $status, $keterangan, $evidence, $elapsed);
        }
    }

    /**
     * Ambil screenshot setelah langkah terakhir berhasil.
     * Nama file: rata (tanpa subfolder) → PBI10_TC.TambahBeasiswa.001.png
     */
    protected function shot(Browser $browser, string $caseId = ''): string
    {
        // caseId boleh kosong; fallback ke nama test atau kombinasi PBI dan caseId
        if ($caseId === '') {
            if (!empty($this->currentCaseId)) {
                $pbiTag = preg_replace('/[^A-Za-z0-9]/', '', $this->currentPbi ?: 'PBI10');
                $caseId = $pbiTag . '_' . $this->currentCaseId;
            } else {
                $caseId = str_replace(['Tests\\Browser\\', '\\', '::test_'], ['', '_', '_'], static::class . '::' . $this->getName());
            }
        }

        // Buat nama file yang aman (izinkan titik, huruf, angka, underscore, strip)
        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $caseId);
        // EXTRA: screenshot() – mengambil screenshot bukti eksekusi test
        $browser->screenshot($filename);

        $this->lastEvidence = $filename . '.png';

        return $filename . '.png';
    }

    // ─────────────────────────────────────────────────────────────
    // Internals
    // ─────────────────────────────────────────────────────────────

    private function writeToCsv(
        string $pbi,
        string $caseId,
        string $scenario,
        string $type,
        string $status,
        string $keterangan,
        string $evidence,
        string $elapsed
    ): void {
        $dir  = base_path('tests/Browser/Results');
        $file = $dir . '/hasil_testing.csv';

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $fp = fopen($file, file_exists($file) ? 'r+' : 'w+');
        flock($fp, LOCK_EX);

        // Baca semua baris yang ada
        $rows   = [];
        $header = ['PBI ID', 'Case ID', 'Test Scenario', 'Type', 'Status', 'Keterangan', 'Evidence', 'Waktu Eksekusi'];
        $found  = false;

        if (filesize($file) > 0) {
            rewind($fp);
            // Lewati BOM jika ada
            $bom = fread($fp, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($fp);
            }
            while (($row = fgetcsv($fp, 0, ',', '"', '\\')) !== false) {
                if (!isset($row[1]) || $row[0] === 'PBI ID') {
                    continue; // skip header
                }
                if ($row[1] === $caseId) {
                    // Upsert: ganti baris yang sama
                    $rows[] = [$pbi, $caseId, $scenario, $type, $status, $keterangan, $evidence, $elapsed];
                    $found  = true;
                } else {
                    $rows[] = $row;
                }
            }
        }

        if (!$found) {
            $rows[] = [$pbi, $caseId, $scenario, $type, $status, $keterangan, $evidence, $elapsed];
        }

        // Tulis ulang file dari awal dengan BOM UTF-8
        rewind($fp);
        ftruncate($fp, 0);
        fwrite($fp, "\xEF\xBB\xBF"); // BOM agar Excel terbaca benar
        fputcsv($fp, $header);
        foreach ($rows as $row) {
            fputcsv($fp, $row);
        }

        flock($fp, LOCK_UN);
        fclose($fp);
    }

    private function findLatestFailureScreenshot(string $caseId): string
    {
        $dir = base_path('tests/Browser/screenshots');
        if (!is_dir($dir)) {
            return '-';
        }

        // Cari file failure-* terbaru
        $files = glob($dir . '/failure-*.png');
        if (empty($files)) {
            return '-';
        }

        usort($files, fn ($a, $b) => filemtime($b) - filemtime($a));

        return basename($files[0]);
    }
}
