<div class="p-4 sm:p-8 space-y-6">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ringkasan stok periode {{ $currentPeriode }}</p>
        </div>
        @if ($activePeriode)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                         {{ $activePeriode->status === 'Open' ? 'bg-green-50 text-green-700 ring-1 ring-green-200' : 'bg-gray-100 text-gray-500' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $activePeriode->status === 'Open' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                Periode {{ $activePeriode->periode }} — {{ $activePeriode->status }}
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-red-200">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                Belum ada periode aktif
            </span>
        @endif
    </div>

    @if (! $activePeriode)
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
            Belum ada periode aktif. Buka periode baru melalui halaman
            <a href="{{ route('periode.index') }}" class="font-semibold underline">Tutup Buku</a>.
        </div>
    @endif

    @if (count($summary) === 0 && $activePeriode)
        <div class="rounded-xl border border-gray-200 bg-white p-8 text-center text-gray-500">
            <p class="text-sm">Belum ada data stok pada periode {{ $currentPeriode }}.</p>
            <a href="{{ route('opname.create') }}" class="mt-3 inline-block text-sm font-medium text-amber-700 underline">
                Input opname pertama
            </a>
        </div>
    @endif

    {{-- Summary cards --}}
    @if (count($summary) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($summary as $item)
                <a href="{{ route('stock.detail', $item->kodeBarang) }}"
                   class="block rounded-xl border border-gray-200 bg-white p-5 shadow-xs hover:border-amber-300 hover:shadow-sm transition-all">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $item->namaBean }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->kodeBarang }}</p>
                        </div>
                        <span class="shrink-0 text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-medium">
                            {{ $item->jenis }}
                        </span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($item->saldoAkhir, 2) }}
                            <span class="text-sm font-normal text-gray-500">{{ $item->satuan }}</span>
                        </p>
                        <div class="mt-2 flex gap-4 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <span class="text-green-600 font-medium">+{{ number_format($item->totalMasuk, 2) }}</span> masuk
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="text-red-500 font-medium">−{{ number_format($item->totalKeluar, 2) }}</span> keluar
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
