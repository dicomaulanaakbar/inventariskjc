<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_belis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
             $table->datetime('tgl_pembelian');
            $table->integer('jumlah_barang');
            $table->decimal('total_bayar', 15, 2);
            $table->enum('status_pembayaran', ['lunas', 'proses', 'batal'])->default('belum bayar');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_belis');
    }
};
