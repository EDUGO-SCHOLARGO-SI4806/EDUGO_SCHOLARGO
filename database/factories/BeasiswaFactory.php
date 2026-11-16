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
            'nama'           => fake()->sentence(4),
            'penyelenggara'  => fake()->company(),
            'deskripsi'      => fake()->paragraph(3),
            'jenjang'        => fake()->randomElement(['D3', 'D4', 'S1', 'S2', 'S3']),
            'kategori'       => fake()->randomElement(['Full Scholarship', 'Partial Scholarship', 'Beasiswa Prestasi', null]),
            'lokasi'         => null,
            'jenis_pendanaan'=> null,
            'nominal'        => fake()->optional()->numberBetween(1_000_000, 50_000_000),
            'kuota'          => fake()->optional()->numberBetween(1, 200),
            'tanggal_buka'   => null,
            'tanggal_tutup'  => null,
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
