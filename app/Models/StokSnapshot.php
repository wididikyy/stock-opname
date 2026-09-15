<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokSnapshot extends Model
{
    protected $fillable = ['periode', 'kode_barang', 'nama_bean', 'jenis', 'kategori', 'saldo_akhir', 'satuan'];
}
