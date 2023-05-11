<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsBarang extends Model
{
    use HasFactory;

    protected $table = "ms_barang";

    public function ms_kategori()
    {
        return $this->belongsTo(MsKategori::class);
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }
}
