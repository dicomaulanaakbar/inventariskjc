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
        Schema::create('barang_juals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->datetime('tgl_jual');
            $table->enum('metode_pembayaran', ['qris', 'tunai', 'transfer'])->default('Tunai');
             $table->decimal('total_harga_jual', 15, 2);
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('retur_id')->nullable()->constrained('returs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_juals');
    }
};
