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
        // Jalankan seeder PackageSeeder
        $this->call(UserSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(LayananSeeder::class);
        $this->call(PelangganSeeder::class);
    }
}
