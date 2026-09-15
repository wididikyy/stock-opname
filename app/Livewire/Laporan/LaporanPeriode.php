<?php

namespace App\Livewire\Laporan;

use App\Exports\LaporanPeriodeExport;
use App\Models\PeriodeLog;
use App\Services\StockService;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPeriode extends Component
{
    public string $selectedPeriode = '';

    public function mount(): void
    {
        $activePeriode = PeriodeLog::where('status', 'Open')->value('periode');
        $this->selectedPeriode = $activePeriode ?? now()->format('Y-m');
    }

    public function exportExcel(): \Symfony\Component\HttpFoundation\StreamedResponse|null
    {
        if (! $this->selectedPeriode) {
            return null;
        }

        $filename = "laporan-{$this->selectedPeriode}.xlsx";
        $periode  = $this->selectedPeriode;

        return response()->streamDownload(function () use ($periode) {
            echo Excel::raw(new LaporanPeriodeExport($periode), \Maatwebsite\Excel\Excel::XLSX);
        }, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    public function render(StockService $service)
    {
        $summary = $this->selectedPeriode
            ? $service->getSummary($this->selectedPeriode)
            : [];

        $periodes = PeriodeLog::orderByDesc('periode')->pluck('periode');

        $totalMasuk = array_sum(array_column($summary, 'totalMasuk'));
        $totalKeluar = array_sum(array_column($summary, 'totalKeluar'));

        return view('livewire.laporan.laporan-periode', compact('summary', 'periodes', 'totalMasuk', 'totalKeluar'));
    }
}
