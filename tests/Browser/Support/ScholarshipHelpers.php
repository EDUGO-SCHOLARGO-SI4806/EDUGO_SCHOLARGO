<?php

namespace Tests\Browser\Support;

use App\Models\Beasiswa;
use App\Models\User;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

trait ScholarshipHelpers
{
    // ──────────────────────────────────────────────────────────────
    //  Buat user admin (tidak ada sistem role, semua user bisa akses)
    // ──────────────────────────────────────────────────────────────
    protected function makeAdmin(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'name'  => 'Admin Test',
            'email' => 'admin.dusk@example.com',
        ], $overrides));
    }

    // ──────────────────────────────────────────────────────────────
    //  Buat Beasiswa lewat factory
    // ──────────────────────────────────────────────────────────────
    protected function makeScholarship(array $overrides = []): Beasiswa
    {
        return Beasiswa::factory()->create($overrides);
    }

    // ──────────────────────────────────────────────────────────────
    //  Isi semua field wajib (dan opsional jika tidak di-override)
    //  dengan data valid pada form create/edit beasiswa.
    //
    //  $overrides key → field name di form (name attribute Blade)
    // ──────────────────────────────────────────────────────────────
    protected function fillValidForm(Browser $browser, array $overrides = []): void
    {
        $defaults = [
            'name'           => 'Beasiswa Uji Otomatis',
            'organizer_name' => 'Lembaga Dusk Testing',
            'description'    => 'Deskripsi beasiswa otomatis untuk pengujian Laravel Dusk.',
            'level'          => 'S1',
            'status'         => 'draft',
            'amount'         => null,          // opsional, dikosongkan
            'quota'          => null,          // opsional
            'start_date'     => null,          // opsional
            'end_date'       => null,          // opsional
            'source_url'     => null,          // opsional
            'category'       => null,          // opsional
        ];

        $data = array_merge($defaults, array_filter($overrides, fn ($v) => $v !== false));

        // Nama
        if (!empty($data['name'])) {
            $browser->type('name', $data['name']);
        }

        // Penyelenggara
        if (!empty($data['organizer_name'])) {
            $browser->type('organizer_name', $data['organizer_name']);
        }

        // Deskripsi
        if (!empty($data['description'])) {
            $browser->type('description', $data['description']);
        }

        // Jenjang (select)
        if (!empty($data['level'])) {
            $browser->select('level', $data['level']);
        }

        // Kategori (select, opsional)
        if (!empty($data['category'])) {
            $browser->select('category', $data['category']);
        }

        // Nominal
        if (!empty($data['amount'])) {
            $browser->type('amount', (string) $data['amount']);
        }

        // Kuota
        if (!empty($data['quota'])) {
            $browser->type('quota', (string) $data['quota']);
        }

        // Tanggal mulai (input[type=date], pakai script untuk keandalan lintas OS)
        if (!empty($data['start_date'])) {
            // EXTRA: script – type() tidak konsisten untuk input[type=date] di semua OS
            $browser->script(
                "document.querySelector('[name=start_date]').value='" . $data['start_date'] . "'"
            );
        }

        // Deadline
        if (!empty($data['end_date'])) {
            // EXTRA: script – same reason
            $browser->script(
                "document.querySelector('[name=end_date]').value='" . $data['end_date'] . "'"
            );
        }

        // Link sumber
        if (!empty($data['source_url'])) {
            $browser->type('source_url', $data['source_url']);
        }

        // Status (select: draft / published)
        if (!empty($data['status'])) {
            $browser->select('status', $data['status']);
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Ambil path fixture; buat file bila belum ada.
    // ──────────────────────────────────────────────────────────────
    protected function fixture(string $name): string
    {
        $dir = base_path('tests/Browser/fixtures');

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $path = $dir . DIRECTORY_SEPARATOR . $name;

        if (!file_exists($path)) {
            match ($name) {
                'poster_1mb.jpg' => $this->createJpegFixture($path, 1 * 1024 * 1024),
                'poster_3mb.jpg' => $this->createJpegFixture($path, 3 * 1024 * 1024),
                'dokumen.pdf'    => $this->createPdfFixture($path),
                default          => throw new \RuntimeException("Fixture tidak dikenal: {$name}"),
            };
        }

        return $path;
    }

    // Buat JPEG valid via base64 lalu pad ke ukuran target.
    // Menggunakan baseline JPEG 1x1 pixel (tidak bergantung ekstensi GD).
    private function createJpegFixture(string $path, int $targetBytes): void
    {
        // JPEG 1x1 pixel baseline, dibuat dengan System.Drawing / Pillow
        $b64 = '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAABAAEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9U6KKKAP/2Q==';
        $jpegData = base64_decode($b64);

        // Pad ke ukuran target dengan null bytes (data setelah EOI abaikan oleh parser)
        $padding = $targetBytes - strlen($jpegData);
        if ($padding > 0) {
            $jpegData .= str_repeat("\x00", $padding);
        }

        file_put_contents($path, $jpegData);
    }

    // Buat PDF minimal yang valid (header saja)
    private function createPdfFixture(string $path): void
    {
        $pdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\nxref\n0 2\n0000000000 65535 f \n0000000009 00000 n \ntrailer\n<< /Size 2 /Root 1 0 R >>\nstartxref\n%%EOF\n";
        file_put_contents($path, $pdf);
    }
}
