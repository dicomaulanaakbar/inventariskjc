<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $fillable = ['nama_supplier', 'kontak'];

    /**
     * Relasi ke Barang (one to many)
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
