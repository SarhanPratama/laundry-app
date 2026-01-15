<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->insert([
            [
                'nama_kategori' => 'Paket Reguler',
                'harga_kategori' => 10000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Paket Express',
                'harga_kategori' => 15000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Paket Kilat',
                'harga_kategori' => 20000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
