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
        DB::table('packages')->insert([
            [
                'nama_paket' => 'Paket Hemat',
                'harga' => 30000.00,
                'waktu_pengerjaan' => 1,
                'satuan_waktu' => 'hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Express',
                'harga' => 50000.00,
                'waktu_pengerjaan' => 6,
                'satuan_waktu' => 'jam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Super Cepat',
                'harga' => 70000.00,
                'waktu_pengerjaan' => 120,
                'satuan_waktu' => 'menit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
