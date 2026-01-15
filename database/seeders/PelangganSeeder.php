<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        DB::table('pelanggan')->insert([

            [
                'kode_pelanggan'=> 'PLG001',
                'nama_pelanggan' => 'Andi Setiawan',
                'no_telfon' => '081234567890',
                'alamat' => 'Jl. Merpati No. 12, Jakarta Selatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pelanggan'=> 'PLG002',
                'nama_pelanggan' => 'Budi Hartono',
                'no_telfon' => '082345678901',
                'alamat' => 'Jl. Anggrek No. 45, Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pelanggan'=> 'PLG003',
                'nama_pelanggan' => 'Citra Dewi',
                'no_telfon' => '083456789012',
                'alamat' => 'Jl. Melati No. 8, Surabaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
