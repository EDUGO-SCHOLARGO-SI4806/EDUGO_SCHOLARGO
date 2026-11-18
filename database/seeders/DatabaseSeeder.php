<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // FR-01 (Salsabila): 4 penyelenggara contoh dengan status berbeda
        $this->call(OrganizerDemoSeeder::class);
    }
}
