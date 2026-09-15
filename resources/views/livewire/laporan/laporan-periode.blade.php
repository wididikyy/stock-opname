<div class="p-4 sm:p-8 space-y-6">
    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Periode</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ringkasan stok per periode</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <label class="text-sm font-medium text-gray-600">Periode:</label>
            <select wire:model.live="selectedPeriode"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-300">
                <option value="">— Pilih periode —</option>
                @foreach ($periodes as $p)
                    <option value="{{ $p }}">{{ $p }}</option>
                @endforeach
            </select>
            @if ($selectedPeriode)
                <button wire:click="exportExcel" wire:loading.attr="disabled" wire:target="exportExcel"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
                    <span wire:loading wire:target="exportExcel">Menyiapkan…</span>
                </button>
            @endif
        </div>
    </div>

    @if (! $selectedPeriode)
        <div class="rounded-xl border border-gray-200 bg-white p-8 text-center text-gray-400 text-sm">
            Pilih periode untuk melihat laporan.
        </div>
    @elseif (count($summary) === 0)
        <div class="rounded-xl border border-gray-200 bg-white p-8 text-center text-gray-400 text-sm">
            Tidak ada data pada periode <strong>{{ $selectedPeriode }}</strong>.
        </div>
    @else
        {{-- Summary totals --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-xs">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Item</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ count($summary) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-xs">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Masuk</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalMasuk, 2) }} kg</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-xs">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Keluar</p>
                <p class="text-2xl font-bold text-red-500 mt-1">{{ number_format($totalKeluar, 2) }} kg</p>
            </div>
        </div>

        {{-- Mobile: cards --}}
        <div class="sm:hidden space-y-3">
            <p class="text-sm font-semibold text-gray-600 px-1">Detail per Kode Barang — {{ $selectedPeriode }}</p>
            @foreach ($summary as $item)
                <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('stock.detail', $item->kodeBarang) }}"
                           class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700 hover:bg-amber-100 hover:text-amber-800">
                            {{ $item->kodeBarang }}
                        </a>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-medium">
                            {{ $item->jenis }}
                        </span>
                    </div>

                    <p class="font-semibold text-gray-900">{{ $item->namaBean }}</p>

                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                        <div class="flex gap-4 text-xs">
                            <span class="text-green-600 font-medium">+{{ number_format($item->totalMasuk, 2) }}</span>
                            <span class="text-red-500 font-medium">−{{ number_format($item->totalKeluar, 2) }}</span>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">
                            {{ number_format($item->saldoAkhir, 2) }}
                            <span class="text-xs font-normal text-gray-400">{{ $item->satuan }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Desktop: table --}}
        <div class="hidden sm:block bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">Detail per Kode Barang — {{ $selectedPeriode }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Bean</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Jenis</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Masuk</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Keluar</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo Akhir</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Satuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($summary as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('stock.detail', $item->kodeBarang) }}"
                                       class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700 hover:bg-amber-100 hover:text-amber-800">
                                        {{ $item->kodeBarang }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-900 font-medium">{{ $item->namaBean }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-medium">{{ $item->jenis }}</span>
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-green-600">
                                    {{ number_format($item->totalMasuk, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-red-500">
                                    {{ number_format($item->totalKeluar, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900">
                                    {{ number_format($item->saldoAkhir, 2) }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-500 text-xs">{{ $item->satuan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
