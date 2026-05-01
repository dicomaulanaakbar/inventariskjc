<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = ['barang_id', 'jenis', 'jumlah', 'keterangan', 'tanggal'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}