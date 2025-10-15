<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('layanan')->insert([
            [
                'nama_layanan' => 'Cuci Kering',
                'harga' => 15000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_layanan' => 'Cuci Setrika',
                'harga' => 20000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_layanan' => 'Setrika Saja',
                'harga' => 10000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
