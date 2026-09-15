<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeLog extends Model
{
    protected $fillable = ['periode', 'tanggal_mulai', 'tanggal_tutup', 'status', 'ditutup_oleh'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_tutup' => 'date',
    ];
}
