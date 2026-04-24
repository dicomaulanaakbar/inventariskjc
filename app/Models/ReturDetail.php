<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturDetail extends Model
{
     use HasFactory;

    protected $table = 'returs_details';
    protected $fillable = [
        'jumlah',
        'return_id',
        'barang_id'
    ];

    /**
     * Relasi ke Return (header retur)
     */
    public function return()
    {
        return $this->belongsTo(ReturBarang::class);
    }

    /**
     * Relasi ke Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
