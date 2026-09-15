<?php

namespace App\Services;

use App\Models\PeriodeLog;
use App\Models\StockEntry;
use App\Models\StokSnapshot;
use Illuminate\Support\Facades\DB;

class PeriodeService
{
    public function __construct(private StockService $stockService) {}

    public function getActivePeriode(): ?PeriodeLog
    {
        return PeriodeLog::where('status', 'Open')->first();
    }

    public function tutupBuku(string $periode, string $ditutupOleh): array
    {
        return DB::transaction(function () use ($periode, $ditutupOleh) {
            $summary = $this->stockService->getSummary($periode);

            foreach ($summary as $item) {
                StokSnapshot::create([
                    'periode'     => $periode,
                    'kode_barang' => $item->kodeBarang,
                    'nama_bean'   => $item->namaBean,
                    'jenis'       => $item->jenis,
                    'kategori'    => $item->kategori,
                    'saldo_akhir' => $item->saldoAkhir,
                    'satuan'      => $item->satuan,
                ]);
            }

            PeriodeLog::where('periode', $periode)->update([
                'tanggal_tutup' => now()->toDateString(),
                'status'        => 'Closed',
                'ditutup_oleh'  => $ditutupOleh,
            ]);

            StockEntry::where('periode', $periode)->update(['locked' => true]);

            return ['periode' => $periode, 'summary' => $summary];
        });
    }

    public function bukaBuku(string $periodeBaru, string $tanggalMulai): void
    {
        DB::transaction(function () use ($periodeBaru, $tanggalMulai) {
            $latestSnapshotPeriode = StokSnapshot::orderByDesc('id')->value('periode');

            if ($latestSnapshotPeriode) {
                $snapshotLalu = StokSnapshot::where('periode', $latestSnapshotPeriode)->get();

                foreach ($snapshotLalu as $s) {
                    StockEntry::create([
                        'tanggal'     => $tanggalMulai,
                        'kode_barang' => $s->kode_barang,
                        'nama_bean'   => $s->nama_bean,
                        'jenis'       => $s->jenis,
                        'kategori'    => $s->kategori,
                        'keterangan'  => 'Opening Stock',
                        'keluar'      => 0,
                        'masuk'       => 0,
                        'saldo'       => $s->saldo_akhir,
                        'satuan'      => $s->satuan,
                        'periode'     => $periodeBaru,
                        'locked'      => false,
                    ]);
                }
            }

            PeriodeLog::create([
                'periode'       => $periodeBaru,
                'tanggal_mulai' => $tanggalMulai,
                'status'        => 'Open',
            ]);
        });
    }
}
