<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
     use HasFactory;

    protected $table = 'barangs';
    protected $fillable = [
<<<<<<< HEAD
        'nama_barang', 'kategori_id', 'spesifikasi', 'stok', 'satuan', 'harga_beli', 'harga_jual'
=======
        'nama_barang',
        'spesifikasi',
        'kategori_id',
        'supplier_id',
        'stok',
        'satuan',
        'harga_jual'
>>>>>>> d83e460b73b6ab92e814ed03f5d4c0f2659493e6
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
