<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Kita gunakan nullable() agar data barang yang sudah ada tidak error
            // karena belum punya supplier_id saat kolom ini dibuat.
            $table->foreignId('supplier_id')
                  ->nullable()
                  ->after('id') // Meletakkan kolom setelah kolom 'id'
                  ->constrained('suppliers')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Menghapus foreign key dan kolomnya jika migrasi di-rollback
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
};
