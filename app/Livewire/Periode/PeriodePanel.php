<?php

namespace App\Livewire\Periode;

use App\Models\PeriodeLog;
use App\Services\PeriodeService;
use App\Services\StockService;
use Livewire\Component;

class PeriodePanel extends Component
{
    public bool $confirmingTutup = false;
    public bool $showBukaBuku = false;
    public string $periodeBaru = '';
    public string $tanggalMulai = '';

    public function mount(): void
    {
        $this->periodeBaru = now()->addMonth()->format('Y-m');
        $this->tanggalMulai = now()->addMonth()->startOfMonth()->format('Y-m-d');
    }

    public function confirmTutupBuku(): void
    {
        $this->confirmingTutup = true;
    }

    public function cancelTutupBuku(): void
    {
        $this->confirmingTutup = false;
    }

    public function tutupBuku(PeriodeService $service): void
    {
        $activePeriode = PeriodeLog::where('status', 'Open')->first();

        if (! $activePeriode) {
            session()->flash('error', 'Tidak ada periode aktif yang bisa ditutup.');
            return;
        }

        try {
            $service->tutupBuku($activePeriode->periode, 'admin');
            $this->confirmingTutup = false;
            $this->showBukaBuku = true;
            session()->flash('success', "Periode {$activePeriode->periode} berhasil ditutup.");
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function bukaBuku(PeriodeService $service): void
    {
        $this->validate([
            'periodeBaru'  => ['required', 'regex:/^\d{4}-\d{2}$/'],
            'tanggalMulai' => ['required', 'date_format:Y-m-d'],
        ]);

        if (PeriodeLog::where('periode', $this->periodeBaru)->exists()) {
            $this->addError('periodeBaru', 'Periode ini sudah ada.');
            return;
        }

        try {
            $service->bukaBuku($this->periodeBaru, $this->tanggalMulai);
            $this->showBukaBuku = false;
            $nextMonth = now()->addMonths(2);
            $this->periodeBaru = $nextMonth->format('Y-m');
            $this->tanggalMulai = $nextMonth->startOfMonth()->format('Y-m-d');
            session()->flash('success', "Periode {$this->periodeBaru} berhasil dibuka.");
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render(StockService $service)
    {
        $activePeriode = PeriodeLog::where('status', 'Open')->first();
        $history = PeriodeLog::orderByDesc('periode')->get();

        $previewSummary = [];
        if ($activePeriode && $this->confirmingTutup) {
            $previewSummary = $service->getSummary($activePeriode->periode);
        }

        return view('livewire.periode.periode-panel', compact('activePeriode', 'history', 'previewSummary'));
    }
}
