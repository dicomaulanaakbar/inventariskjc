<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
     use HasFactory;

    protected $table = 'barangs';
    protected $fillable = [
        'nama_barang',
        'spesifikasi',
        'kategori_id',
        'supplier_id',
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
}
