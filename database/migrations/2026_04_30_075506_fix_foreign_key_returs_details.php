<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::dropIfExists('returs_details');

    Schema::create('returs_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('return_id');
        $table->unsignedBigInteger('barang_id');
        $table->integer('jumlah');
        $table->timestamps();

        $table->foreign('return_id')->references('id')->on('returs')->onDelete('cascade'); // 🔥 FIX
        $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
    });
    }
};
