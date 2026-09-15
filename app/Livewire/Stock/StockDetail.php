<?php

namespace App\Livewire\Stock;

use App\Models\StockEntry;
use Livewire\Component;

class StockDetail extends Component
{
    public string $kodeBarang = '';

    public function mount(string $kodeBarang): void
    {
        $this->kodeBarang = $kodeBarang;
    }

    public function render()
    {
        $entries = StockEntry::where('kode_barang', $this->kodeBarang)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $bean = $entries->first();

        return view('livewire.stock.stock-detail', compact('entries', 'bean'));
    }
}
