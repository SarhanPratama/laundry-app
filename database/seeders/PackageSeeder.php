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
        DB::table('package')->insert([
            [
                'nama_paket' => 'Paket Reguler',
                'harga' => 10000.00,
                'waktu' => '2 Hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Express',
                'harga' => 15000.00,
                'waktu' => '1 Hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Kilat',
                'harga' => 20000.00,
                'waktu' => '6 Jam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
