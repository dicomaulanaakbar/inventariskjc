<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturBarang extends Model
{
     use HasFactory;

    protected $table = 'returs';
    protected $fillable = [
        'tgl_return',
        'alasan'
    ];

    protected $casts = [
        'tgl_return' => 'datetime',
    ];

    /**
     * Relasi ke BarangJual (satu return untuk satu penjualan)
     */
    public function barangJual()
    {
        return $this->hasOne(BarangJual::class);
    }

    /**
     * Relasi ke ReturnDetail (detail barang yang diretur)
     */
    public function details()
    {
        return $this->hasMany(ReturDetail::class);
    }
}
