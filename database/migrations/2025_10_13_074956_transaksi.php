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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->dateTime('tanggal_transaksi')->default(now());
            $table->decimal('total_harga', 10, 2);

            $table->enum('status_pengerjaan', [
                'Belum Siap',
                'Sudah Siap'
            ])->default('Belum Siap');

            $table->enum('status_pembayaran', [
                'Belum Dibayar',
                'Sudah Dibayar'
            ])->default('Belum Dibayar');

            $table->enum('status_pengambilan', [
                'Belum Diambil',
                'Sudah Diambil'
            ])->default('Belum Diambil');
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};