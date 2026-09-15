<?php

namespace App\Exports;

use App\Services\StockService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPeriodeExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(private string $periode) {}

    public function array(): array
    {
        $summary = app(StockService::class)->getSummary($this->periode);

        return collect($summary)->map(fn ($item) => [
            $item->kodeBarang,
            $item->namaBean,
            $item->jenis,
            $item->kategori,
            (float) $item->totalMasuk,
            (float) $item->totalKeluar,
            (float) $item->saldoAkhir,
            $item->satuan,
        ])->toArray();
    }

    public function headings(): array
    {
        return ['Kode Barang', 'Nama Bean', 'Jenis', 'Kategori',
                'Total Masuk', 'Total Keluar', 'Saldo Akhir', 'Satuan'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }

    public function title(): string
    {
        return "Laporan {$this->periode}";
    }
}
