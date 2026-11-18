<?php

namespace Database\Factories;

use App\Models\Beasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Beasiswa>
 */
class BeasiswaFactory extends Factory
{
    protected $model = Beasiswa::class;

    public function definition(): array
    {
        return [
            'nama'           => fake()->randomElement([
                'Beasiswa Bakti Negeri',
                'Beasiswa Garuda Muda',
                'Beasiswa Pelita Ilmu',
                'Beasiswa Harapan Bangsa',
                'Beasiswa Inovasi Negeri',
                'Beasiswa Karya Anak Bangsa',
                'Beasiswa Sinar Harapan',
                'Beasiswa Nusantara Peduli',
                'Beasiswa Bina Cendekia',
                'Beasiswa Teknologi Maju',
            ]) . ' ' . fake()->numerify('###'),
            'penyelenggara'  => fake()->randomElement([
                'Yayasan Bakti Negeri', 'Pelita Education', 'Garuda Scholar',
                'Harapan Bangsa Foundation', 'Inovasi Negeri',
            ]),
            'deskripsi'      => fake()->paragraph(2),
            'jenjang'        => fake()->randomElement(['D3', 'D4', 'S1', 'S2', 'S3']),
            'kategori'       => fake()->randomElement(['Full Scholarship', 'Partial Scholarship', 'Beasiswa Prestasi', null]),
            'lokasi'         => fake()->randomElement(['Dalam negeri', 'Luar negeri', 'Teknologi']),
            'jenis_pendanaan'=> fake()->randomElement(['Pendanaan Penuh', 'Pendanaan Parsial']),
            'nominal'        => fake()->optional()->numberBetween(1_000_000, 50_000_000),
            'kuota'          => fake()->optional()->numberBetween(1, 200),
            'tanggal_buka'   => null,
            'tanggal_tutup'  => now()->addDays(rand(30, 90)),
            'link_sumber'    => null,
            'poster'         => null,
            'status'         => 'draft',
        ];
    }

    /** State: Beasiswa sudah published */
    public function published(): static
    {
        return $this->state(fn (array $attr) => ['status' => 'published']);
    }

    /** State: Beasiswa sudah soft-deleted */
    public function deleted(): static
    {
        return $this->state(fn (array $attr) => [
            'deleted_at' => now(),
        ]);
    }

    /** State: Beasiswa punya poster (path dummy, file tidak harus ada) */
    public function withPoster(string $path = 'posters/dummy.jpg'): static
    {
        return $this->state(fn (array $attr) => ['poster' => $path]);
    }

    /** State: Punya tanggal buka dan tutup */
    public function withDates(?string $buka = null, ?string $tutup = null): static
    {
        return $this->state(fn (array $attr) => [
            'tanggal_buka'  => $buka  ?? now()->format('Y-m-d'),
            'tanggal_tutup' => $tutup ?? now()->addDays(30)->format('Y-m-d'),
        ]);
    }
}
