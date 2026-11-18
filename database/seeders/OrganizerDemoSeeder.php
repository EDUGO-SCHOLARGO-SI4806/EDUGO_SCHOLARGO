<?php

namespace Database\Seeders;

use App\Enums\OrganizerDocumentType;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Data contoh untuk demo FR-01 (semua dummy). Empat penyelenggara dengan kondisi berbeda:
 *
 *  penyelenggara.baru@scholargo.test       belum mengirim dokumen  -> PBI-01 / PBI-02
 *  penyelenggara.menunggu@scholargo.test   status menunggu         -> PBI-03
 *  penyelenggara.ditolak@scholargo.test    status ditolak + catatan-> PBI-03 (unggah ulang)
 *  penyelenggara.disetujui@scholargo.test  status disetujui        -> PBI-03
 *
 * Password semuanya: password
 */
class OrganizerDemoSeeder extends Seeder
{
    private const PDF = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources <<>> /MediaBox [0 0 612 792] >>\nendobj\ntrailer\n<< /Size 4 /Root 1 0 R >>\n%%EOF\n";

    public function run(): void
    {
        $this->seedOrganizer('baru', 'Rina Wulandari');

        $this->seedOrganizer('menunggu', 'Dimas Prakoso', [
            'institution_name' => 'Yayasan Cendekia Nusantara',
            'representative_name' => 'Dimas Prakoso',
            'verification_status' => OrganizerProfile::STATUS_PENDING,
        ]);

        $this->seedOrganizer('ditolak', 'Sari Anggraini', [
            'institution_name' => 'Global Education Foundation',
            'representative_name' => 'Sari Anggraini',
            'verification_status' => OrganizerProfile::STATUS_REJECTED,
            'verification_note' => 'Akta pendirian yang diunggah buram dan tidak terbaca. Mohon unggah ulang dengan hasil scan yang jelas, serta pastikan surat kuasa sudah ditandatangani.',
            'reviewed_at' => now(),
        ]);

        $this->seedOrganizer('disetujui', 'Hendra Gunawan', [
            'institution_name' => 'Lembaga Beasiswa Prestasi Bangsa',
            'representative_name' => 'Hendra Gunawan',
            'verification_status' => OrganizerProfile::STATUS_APPROVED,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * @param  array<string, mixed>|null  $profileData  null = belum mengirim dokumen
     */
    private function seedOrganizer(string $key, string $name, ?array $profileData = null): void
    {
        $user = User::updateOrCreate(
            ['email' => "penyelenggara.{$key}@scholargo.test"],
            ['name' => $name, 'password' => 'password', 'role' => User::ROLE_ORGANIZER],
        );

        if ($profileData === null) {
            return;
        }

        $profile = OrganizerProfile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData + ['submitted_at' => now()],
        );

        if ($profile->documents()->exists()) {
            return;
        }

        foreach (OrganizerDocumentType::cases() as $type) {
            $path = config('scholargo.documents_directory')."/demo-{$key}-{$type->value}.pdf";

            Storage::disk('local')->put($path, self::PDF);

            $profile->documents()->create([
                'type' => $type->value,
                'original_name' => "{$type->value}.pdf",
                'path' => $path,
                'mime_type' => 'application/pdf',
                'size_bytes' => strlen(self::PDF),
            ]);
        }
    }
}
