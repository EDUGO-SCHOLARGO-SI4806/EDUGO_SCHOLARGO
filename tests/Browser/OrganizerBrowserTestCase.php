<?php

namespace Tests\Browser;

use App\Enums\OrganizerDocumentType;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use RuntimeException;
use Tests\DuskTestCase;

/**
 * Dasar untuk semua tes browser FR-01 (PBI-01, PBI-02, PBI-03).
 * Berisi pembuat data contoh dan pembuat file contoh (PDF/JPG/TXT) di folder temp.
 */
abstract class OrganizerBrowserTestCase extends DuskTestCase
{
    use DatabaseMigrations;

    private const PDF = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources <<>> /MediaBox [0 0 612 792] >>\nendobj\ntrailer\n<< /Size 4 /Root 1 0 R >>\n%%EOF\n";

    /** JPEG 1x1 piksel yang valid. */
    private const JPG_BASE64 = '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA=';

    protected string $fixtureDir;

    /**
     * Pengaman: jangan pernah menjalankan tes (yang menghapus semua tabel) di database selain database khusus Dusk.
     */
    protected function beforeRefreshingDatabase(): void
    {
        $database = (string) config('database.connections.'.config('database.default').'.database');

        if (! str_contains($database, 'dusk')) {
            throw new RuntimeException(
                "Tes dihentikan demi keamanan: database '{$database}' bukan database khusus Dusk. ".
                'Pastikan file .env.dusk.local ada dan memakai DB_DATABASE=scholargo_dusk.'
            );
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixtureDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'scholargo-dusk-'.uniqid();
        mkdir($this->fixtureDir, 0777, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->fixtureDir.DIRECTORY_SEPARATOR.'*') ?: [] as $file) {
            @unlink($file);
        }
        @rmdir($this->fixtureDir);

        // Hanya hapus folder khusus tes (berakhiran "dusk"), supaya data demo tidak ikut terhapus.
        $directory = (string) config('scholargo.documents_directory');

        if (str_ends_with($directory, 'dusk')) {
            Storage::disk('local')->deleteDirectory($directory);
        }

        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // Data contoh
    // ------------------------------------------------------------------

    protected function createOrganizer(array $attributes = []): User
    {
        return User::factory()->create(array_merge(['role' => User::ROLE_ORGANIZER], $attributes));
    }

    /**
     * Penyelenggara yang sudah mengirim 5 dokumen dengan status tertentu.
     */
    protected function createSubmittedOrganizer(
        string $status = OrganizerProfile::STATUS_PENDING,
        ?string $note = null,
    ): User {
        $user = $this->createOrganizer();

        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'institution_name' => 'Yayasan Cendekia Nusantara',
            'representative_name' => 'Dimas Prakoso',
            'verification_status' => $status,
            'verification_note' => $note,
            'submitted_at' => now(),
        ]);

        foreach (OrganizerDocumentType::cases() as $type) {
            $path = config('scholargo.documents_directory').'/'.uniqid($type->value.'-').'.pdf';
            Storage::disk('local')->put($path, self::PDF);

            $profile->documents()->create([
                'type' => $type->value,
                'original_name' => $type->value.'-lama.pdf',
                'path' => $path,
                'mime_type' => 'application/pdf',
                'size_bytes' => strlen(self::PDF),
            ]);
        }

        return $user;
    }

    // ------------------------------------------------------------------
    // File contoh untuk diunggah
    // ------------------------------------------------------------------

    /** PDF valid. $paddingKb menambah ukuran file (dipakai untuk tes batas 2 MB). */
    protected function pdfFixture(string $name, int $paddingKb = 0): string
    {
        return $this->writeFixture($name, self::PDF.str_repeat('0', $paddingKb * 1024));
    }

    protected function jpgFixture(string $name): string
    {
        return $this->writeFixture($name, base64_decode(self::JPG_BASE64));
    }

    /** File teks biasa - format yang TIDAK diperbolehkan. */
    protected function textFixture(string $name): string
    {
        return $this->writeFixture($name, 'ini bukan dokumen legalitas');
    }

    private function writeFixture(string $name, string $content): string
    {
        $path = $this->fixtureDir.DIRECTORY_SEPARATOR.$name;
        file_put_contents($path, $content);

        return $path;
    }

    /**
     * Lampirkan file valid ke semua kolom dokumen di form pengajuan (kecuali yang dilewati).
     * Identitas perwakilan sengaja memakai JPG supaya kedua format yang diizinkan ikut teruji.
     *
     * @param  array<int, OrganizerDocumentType>  $skip
     */
    protected function attachValidDocuments(Browser $browser, array $skip = []): void
    {
        foreach (OrganizerDocumentType::cases() as $type) {
            if (in_array($type, $skip, true)) {
                continue;
            }

            $file = $type === OrganizerDocumentType::IdentitasPerwakilan
                ? $this->jpgFixture('identitas-perwakilan.jpg')
                : $this->pdfFixture($type->value.'.pdf');

            $browser->attach("documents[{$type->value}]", $file);
        }
    }

    protected function fillInstitutionData(Browser $browser): void
    {
        $browser->type('institution_name', 'Yayasan Cendekia Nusantara')
            ->type('representative_name', 'Dimas Prakoso');
    }
}
