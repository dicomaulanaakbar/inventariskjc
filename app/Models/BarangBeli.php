<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangBeli extends Model
{
    use HasFactory;

    protected $table = 'barang_belis';
    protected $fillable = [
        'tgl_pembelian',
        'jumlah_barang',
        'total_biaya',
        'status_pembayaran',
        'user_id',
        'barang_id'
    ];

    protected $casts = [
        'tgl_pembelian' => 'datetime',
    ];

    /**
     * Relasi ke User (pencatat transaksi)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
