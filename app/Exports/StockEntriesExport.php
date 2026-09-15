<?php

namespace App\Exports;

use App\Models\StockEntry;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockEntriesExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function __construct(
        private ?string $periodeFilter = null,
        private ?string $kodeFilter = null,
        private ?string $keteranganFilter = null,
    ) {}

    public function array(): array
    {
        return StockEntry::query()
            ->when($this->periodeFilter, fn ($q) => $q->where('periode', $this->periodeFilter))
            ->when($this->kodeFilter, fn ($q) => $q->where('kode_barang', $this->kodeFilter))
            ->when($this->keteranganFilter, fn ($q) => $q->where('keterangan', $this->keteranganFilter))
            ->orderBy('tanggal')
            ->orderBy('kode_barang')
            ->get()
            ->map(fn ($e) => [
                $e->tanggal->format('d/m/Y'),
                $e->kode_barang,
                $e->nama_bean,
                $e->jenis,
                $e->kategori,
                $e->keterangan,
                (float) $e->masuk,
                (float) $e->keluar,
                (float) $e->saldo,
                $e->satuan,
                $e->periode,
            ])
            ->toArray();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Kode Barang', 'Nama Bean', 'Jenis', 'Kategori',
                'Keterangan', 'Masuk', 'Keluar', 'Saldo', 'Satuan', 'Periode'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
