<?php

namespace App\Livewire;

use App\Models\PeriodeLog;
use App\Services\StockService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(StockService $service)
    {
        $activePeriode = PeriodeLog::where('status', 'Open')->first();
        $currentPeriode = $activePeriode?->periode ?? now()->format('Y-m');
        $summary = $service->getSummary($currentPeriode);

        return view('livewire.dashboard', [
            'summary'       => $summary,
            'activePeriode' => $activePeriode,
            'currentPeriode' => $currentPeriode,
        ]);
    }
}
