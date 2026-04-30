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
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->datetime('tgl_return');
            $table->string('alasan_retur', 100);
            $table->enum('status_retur', ['sukses', 'proses'])->default('belum ada');

            $table->foreignId('barang_jual_id')->constrained('barang_juals')->onDelete('cascade');
            $table->text('keterangan')->nullable();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returs');
    }
};
