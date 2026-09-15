<?php

namespace App\DataTransferObjects;

class StockSummary
{
    public function __construct(
        public string $kodeBarang,
        public string $namaBean,
        public string $jenis,
        public string $kategori,
        public string $satuan,
        public float $totalMasuk,
        public float $totalKeluar,
        public float $saldoAkhir,
    ) {}
}
