<?php

namespace App\Services;

use App\DataTransferObjects\StockSummary;
use App\Models\PeriodeLog;
use App\Models\StockEntry;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function getAllEntries(?string $periode = null, ?string $kodeBarang = null)
    {
        return StockEntry::query()
            ->when($periode, fn ($q) => $q->where('periode', $periode))
            ->when($kodeBarang, fn ($q) => $q->where('kode_barang', $kodeBarang))
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();
    }

    public function addEntry(array $data): StockEntry
    {
        return DB::transaction(function () use ($data) {
            $activePeriode = PeriodeLog::where('periode', $data['periode'])->first();

            if (! $activePeriode || $activePeriode->status !== 'Open') {
                throw new \RuntimeException("Periode {$data['periode']} sudah ditutup atau tidak ditemukan.");
            }

            $lastEntry = StockEntry::query()
                ->where('kode_barang', $data['kode_barang'])
                ->where('periode', $data['periode'])
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $saldoSebelumnya = $lastEntry ? (float) $lastEntry->saldo : 0;
            $data['saldo'] = $saldoSebelumnya + (float) $data['masuk'] - (float) $data['keluar'];

            return StockEntry::create($data);
        });
    }

    public function getSummary(?string $periode = null): array
    {
        $entries = $this->getAllEntries($periode);

        return $entries
            ->groupBy('kode_barang')
            ->map(function ($group) {
                $first = $group->first();
                $last = $group->last();

                return new StockSummary(
                    kodeBarang: $first->kode_barang,
                    namaBean: $first->nama_bean,
                    jenis: $first->jenis,
                    kategori: $first->kategori,
                    satuan: $first->satuan,
                    totalMasuk: (float) $group->sum('masuk'),
                    totalKeluar: (float) $group->sum('keluar'),
                    saldoAkhir: (float) $last->saldo,
                );
            })
            ->values()
            ->all();
    }

    public function getKodeOptions(): array
    {
        return StockEntry::select('kode_barang', 'nama_bean', 'jenis', 'kategori', 'satuan')
            ->distinct()
            ->orderBy('kode_barang')
            ->get()
            ->toArray();
    }
}
