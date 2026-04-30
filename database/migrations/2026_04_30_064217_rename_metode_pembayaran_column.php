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
    if (Schema::hasColumn('barang_juals', 'metode pembayaran')) {
        Schema::table('barang_juals', function (Blueprint $table) {
            $table->renameColumn('metode pembayaran', 'metode_pembayaran');
        });
    }
}

public function down()
{
    Schema::table('barang_juals', function (Blueprint $table) {
        $table->renameColumn('metode_pembayaran', 'metode pembayaran');
    });
}
};
