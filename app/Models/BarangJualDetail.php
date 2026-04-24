<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangJualDetail extends Model
{
     use HasFactory;

    protected $table = 'barang_jual_details';
    protected $fillable = [
        'jumlah',
        'barang_jual_id',
        'barang_id'
    ];

    /**
     * Relasi ke BarangJual (header penjualan)
     */
    public function barangJual()
    {
        return $this->belongsTo(BarangJual::class);
    }

    /**
     * Relasi ke Barang (master)
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
