<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id(); // Kolom NO (auto increment)
            $table->string('nama_paket', 100); // Kolom NAMA PAKET
            $table->decimal('harga', 10, 2); // Kolom HARGA (misal pakai format 100000.00)
            $table->string('waktu_pengerjaan', 50);
            // $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); // Kolom STATUS
            $table->string('waktu', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
