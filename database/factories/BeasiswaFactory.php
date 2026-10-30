<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BeasiswaFactory extends Factory
{
    public function definition(): array
    {
        $nama = fake()->unique()->randomElement([
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
        ]);

        return [
            'nama'            => $nama,
            'penyelenggara'   => fake()->randomElement([
                'Yayasan Bakti Negeri', 'Pelita Education', 'Garuda Scholar',
                'Harapan Bangsa Foundation', 'Inovasi Negeri',
            ]),
            'jenjang'         => fake()->randomElement(['D3', 'D4', 'S1', 'S2', 'S3']),
            'lokasi'          => fake()->randomElement(['Dalam negeri', 'Luar negeri', 'Teknologi']),
            'jenis_pendanaan' => fake()->randomElement(['Pendanaan Penuh', 'Pendanaan Parsial']),
            'deskripsi'       => 'Program beasiswa untuk mahasiswa berprestasi.',
            'tanggal_tutup'   => now()->addDays(rand(1, 60)),
        ];
    }
}