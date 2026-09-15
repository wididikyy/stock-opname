<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $fillable = [
        'tanggal', 'kode_barang', 'nama_bean', 'jenis', 'kategori',
        'keterangan', 'keluar', 'masuk', 'saldo', 'satuan', 'periode', 'locked',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'keluar'  => 'decimal:2',
        'masuk'   => 'decimal:2',
        'saldo'   => 'decimal:2',
        'locked'  => 'boolean',
    ];

    public function scopeKodeBarang($query, string $kode)
    {
        return $query->where('kode_barang', $kode);
    }

    public function scopePeriode($query, string $periode)
    {
        return $query->where('periode', $periode);
    }
}
