<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangJual extends Model
{
     use HasFactory;

    protected $table = 'barang_juals';
    protected $fillable = [
    'tgl_jual', 'metode_pembayaran', 'total_harga_jual', 'user_id'
];

    protected $casts = [
        'tgl_jual' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function retur()
    {
        return $this->hasOne(ReturBarang::class, 'barang_jual_id');
    }

    public function details()
    {
        return $this->hasMany(BarangJualDetail::class, 'barang_jual_id');
    }
}
