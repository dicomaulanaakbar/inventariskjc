<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangJual extends Model
{
     use HasFactory;

    protected $table = 'barang_juals';
    protected $fillable = [
        'tgl_jual',
        'metode_pembayaran'
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
}
