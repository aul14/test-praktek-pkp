<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsKategori extends Model
{
    use HasFactory;

    protected $table = "ms_kategori";

    public function ms_barang()
    {
        return $this->hasOne(MsBarang::class);
    }
}
