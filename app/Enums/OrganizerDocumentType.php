<?php

namespace App\Enums;

/**
 * Jenis dokumen legalitas yang wajib diunggah penyelenggara beasiswa (PBI-01).
 * Semua jenis di bawah ini WAJIB (dicek di PBI-02).
 */
enum OrganizerDocumentType: string
{
    case AktaPendirian = 'akta_pendirian';
    case SkPengesahan = 'sk_pengesahan';
    case NibNpwp = 'nib_npwp';
    case SuratKuasa = 'surat_kuasa';
    case IdentitasPerwakilan = 'identitas_perwakilan';

    public function label(): string
    {
        return match ($this) {
            self::AktaPendirian => 'Akta Pendirian Lembaga',
            self::SkPengesahan => 'SK Pengesahan Badan Hukum',
            self::NibNpwp => 'NIB / NPWP Lembaga',
            self::SuratKuasa => 'Surat Kuasa / Surat Tugas Perwakilan',
            self::IdentitasPerwakilan => 'Identitas Perwakilan (KTP)',
        };
    }

    public function hint(): string
    {
        return match ($this) {
            self::AktaPendirian => 'Akta pendirian lembaga dari notaris.',
            self::SkPengesahan => 'SK pengesahan badan hukum dari Kemenkumham.',
            self::NibNpwp => 'Nomor Induk Berusaha (NIB) atau NPWP atas nama lembaga.',
            self::SuratKuasa => 'Surat kuasa atau surat tugas yang menunjuk perwakilan lembaga.',
            self::IdentitasPerwakilan => 'KTP perwakilan lembaga yang mengelola akun.',
        };
    }
}
