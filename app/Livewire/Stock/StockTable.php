<?php

namespace App\Livewire\Stock;

use App\Exports\StockEntriesExport;
use App\Models\PeriodeLog;
use App\Models\StockEntry;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StockTable extends Component
{
    use WithPagination;

    public string $kodeFilter = '';
    public string $periodeFilter = '';
    public string $keteranganFilter = '';
    public string $sortBy = 'tanggal';
    public string $sortDir = 'asc';

    public function updatedKodeFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPeriodeFilter(): void
    {
        $this->resetPage();
    }

    public function updatedKeteranganFilter(): void
    {
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function exportExcel(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $periode  = $this->periodeFilter ?: now()->format('Ymd');
        $filename = "mutasi-stok-{$periode}.xlsx";
        $export   = new StockEntriesExport($this->periodeFilter, $this->kodeFilter, $this->keteranganFilter);

        return response()->streamDownload(function () use ($export) {
            echo Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        }, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    public function render()
    {
        $entries = StockEntry::query()
            ->when($this->kodeFilter, fn ($q) => $q->where('kode_barang', $this->kodeFilter))
            ->when($this->periodeFilter, fn ($q) => $q->where('periode', $this->periodeFilter))
            ->when($this->keteranganFilter, fn ($q) => $q->where('keterangan', $this->keteranganFilter))
            ->orderBy($this->sortBy, $this->sortDir)
            ->orderBy('id', $this->sortDir)
            ->paginate(25);

        $periodes = PeriodeLog::orderByDesc('periode')->pluck('periode');
        $kodes = StockEntry::select('kode_barang', 'nama_bean')
            ->distinct()
            ->orderBy('kode_barang')
            ->get();

        return view('livewire.stock.stock-table', compact('entries', 'periodes', 'kodes'));
    }
}
