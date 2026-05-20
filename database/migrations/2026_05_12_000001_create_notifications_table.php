<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'masuk' or 'keluar'
            $table->morphs('notifiable');
            $table->timestamps();
        });

        // Backfill: create notifications from existing BarangBeli records
        $barangBeli = DB::table('barang_belis')->orderBy('created_at')->get();
        foreach ($barangBeli as $beli) {
            DB::table('notifications')->insert([
                'type' => 'masuk',
                'notifiable_id' => $beli->id,
                'notifiable_type' => 'App\Models\BarangBeli',
                'created_at' => $beli->created_at ?? now(),
                'updated_at' => $beli->updated_at ?? now(),
            ]);
        }

        // Backfill: create notifications from existing BarangJualDetail records
        $detail = DB::table('barang_jual_details')->orderBy('created_at')->get();
        foreach ($detail as $d) {
            DB::table('notifications')->insert([
                'type' => 'keluar',
                'notifiable_id' => $d->id,
                'notifiable_type' => 'App\Models\BarangJualDetail',
                'created_at' => $d->created_at ?? now(),
                'updated_at' => $d->updated_at ?? now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
