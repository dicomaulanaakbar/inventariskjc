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
        Schema::create('barang_jual_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('jumlah');
            $table->foreignId('barang_jual_id')->constrained('barang_juals')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_jual_details');
    }
};
