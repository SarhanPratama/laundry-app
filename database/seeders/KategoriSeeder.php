<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->insert([
            [
                'nama_kategori' => 'Paket Hemat',
                'harga_kategori' => 30000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Express',
                'harga_kategori' => 50000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Super Cepat',
                'harga' => 70000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
