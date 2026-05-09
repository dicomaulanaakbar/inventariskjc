<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'barangs';
    protected $fillable = [
        'nama_barang', 'kategori_id', 'spesifikasi', 'gambar', 'stok', 'satuan', 'harga_beli', 'harga_jual'
    ];

    /**
     * Relasi ke Kategori (belongs to)
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi ke Supplier (belongs to)
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi ke BarangBeli (one to many)
     */
    public function barangBeli()
    {
        return $this->hasMany(BarangBeli::class);
    }

    /**
     * Relasi ke BarangJualDetail (one to many)
     */
    public function barangJualDetails()
    {
        return $this->hasMany(BarangJualDetail::class);
    }

    /**
     * Relasi ke ReturnDetail (one to many)
     */
    public function returnDetails()
    {
        return $this->hasMany(ReturDetail::class);
    }

     public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}
