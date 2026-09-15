<?php

namespace App\Livewire\Opname;

use App\Models\PeriodeLog;
use App\Models\StockEntry;
use App\Services\StockService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class StockForm extends Component
{
    #[Validate('required|date_format:Y-m-d')]
    public string $tanggal = '';

    #[Validate('required|string|max:50')]
    public string $kode_barang = '';

    #[Validate('required|string|max:100')]
    public string $nama_bean = '';

    #[Validate('required|string|max:50')]
    public string $jenis = '';

    #[Validate('required|string|max:50')]
    public string $kategori = 'Roasted Bean';

    #[Validate('required|string|max:50')]
    public string $keterangan = '';

    #[Validate('required|numeric|min:0')]
    public float $keluar = 0;

    #[Validate('required|numeric|min:0')]
    public float $masuk = 0;

    #[Validate('required|string|max:20')]
    public string $satuan = 'kg';

    public string $kodePrefix = 'RB';
    public array $kodeOptions = [];
    public ?string $activePeriode = null;

    public function mount(): void
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->loadOptions();
    }

    public function loadOptions(): void
    {
        $this->kodeOptions = StockEntry::select('kode_barang', 'nama_bean', 'jenis', 'kategori', 'satuan')
            ->distinct()
            ->orderBy('kode_barang')
            ->get()
            ->toArray();

        $this->activePeriode = PeriodeLog::where('status', 'Open')->value('periode');
    }

    public function generateKodeBarang(): void
    {
        $prefix = strtoupper(trim($this->kodePrefix)) ?: 'RB';

        $lastCode = StockEntry::where('kode_barang', 'like', $prefix . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(kode_barang, "-", -1) AS UNSIGNED) DESC')
            ->value('kode_barang');

        if ($lastCode) {
            $lastNum = (int) substr($lastCode, strrpos($lastCode, '-') + 1);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        $this->kode_barang = $prefix . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function fillFromKode(): void
    {
        if (! $this->kode_barang) {
            return;
        }

        $entry = StockEntry::where('kode_barang', $this->kode_barang)
            ->latest('id')
            ->first();

        if ($entry) {
            $this->nama_bean = $entry->nama_bean;
            $this->jenis = $entry->jenis;
            $this->kategori = $entry->kategori;
            $this->satuan = $entry->satuan;
        }
    }

    public function save(StockService $service): void
    {
        $data = $this->validate();
        $data['periode'] = substr($data['tanggal'], 0, 7);

        try {
            $service->addEntry($data);
            $this->loadOptions();
            $this->dispatch('entry-saved');
            session()->flash('success', 'Data opname berhasil disimpan.');
            $this->reset(['keluar', 'masuk', 'keterangan']);
            $this->tanggal = now()->format('Y-m-d');
        } catch (\RuntimeException $e) {
            $this->addError('kode_barang', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.opname.stock-form');
    }
}
