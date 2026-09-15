<div class="p-4 sm:p-8 space-y-5">
    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mutasi Stok</h1>
            <p class="text-sm text-gray-500 mt-0.5">Semua transaksi masuk dan keluar</p>
        </div>
        <button wire:click="exportExcel" wire:loading.attr="disabled" wire:target="exportExcel"
                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
            <span wire:loading wire:target="exportExcel">Menyiapkan…</span>
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <select wire:model.live="periodeFilter"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-300">
            <option value="">Semua Periode</option>
            @foreach ($periodes as $p)
                <option value="{{ $p }}">{{ $p }}</option>
            @endforeach
        </select>

        <select wire:model.live="kodeFilter"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-300">
            <option value="">Semua Kode</option>
            @foreach ($kodes as $k)
                <option value="{{ $k->kode_barang }}">{{ $k->kode_barang }} — {{ $k->nama_bean }}</option>
            @endforeach
        </select>

        <select wire:model.live="keteranganFilter"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-300">
            <option value="">Semua Keterangan</option>
            <option value="Opening Stock">Opening Stock</option>
            <option value="Production In">Production In</option>
            <option value="Sales Out">Sales Out</option>
        </select>

        @if ($kodeFilter || $periodeFilter || $keteranganFilter)
            <button wire:click="$set('kodeFilter', ''); $set('periodeFilter', ''); $set('keteranganFilter', '')"
                    class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 underline">
                Reset filter
            </button>
        @endif
    </div>

    {{-- Mobile: card list --}}
    <div class="sm:hidden space-y-3">
        @forelse ($entries as $entry)
            <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3 {{ $entry->locked ? 'opacity-70' : '' }}">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-400">{{ $entry->tanggal->format('d M Y') }}</span>
                    <div class="flex items-center gap-1.5">
                        <span class="font-mono text-xs text-gray-500">{{ $entry->periode }}</span>
                        @if ($entry->locked)
                            <span class="text-xs text-gray-400" title="Locked">🔒</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('stock.detail', $entry->kode_barang) }}"
                       class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700 hover:bg-amber-100 hover:text-amber-800">
                        {{ $entry->kode_barang }}
                    </a>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ match($entry->keterangan) {
                            'Production In' => 'bg-green-50 text-green-700',
                            'Sales Out'     => 'bg-red-50 text-red-600',
                            default         => 'bg-blue-50 text-blue-700',
                        } }}">
                        {{ $entry->keterangan }}
                    </span>
                </div>

                <p class="font-semibold text-gray-900 text-sm">{{ $entry->nama_bean }}</p>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <div class="flex gap-4 text-xs">
                        <span class="text-green-600 font-medium">
                            +{{ $entry->masuk > 0 ? number_format($entry->masuk, 2) : '0.00' }}
                        </span>
                        <span class="text-red-500 font-medium">
                            −{{ $entry->keluar > 0 ? number_format($entry->keluar, 2) : '0.00' }}
                        </span>
                    </div>
                    <p class="font-bold text-gray-900 text-sm">
                        {{ number_format($entry->saldo, 2) }}
                        <span class="text-xs font-normal text-gray-400">{{ $entry->satuan }}</span>
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 py-10 text-center text-gray-400 text-sm">
                Tidak ada data ditemukan.
            </div>
        @endforelse
    </div>

    {{-- Desktop: table --}}
    <div class="hidden sm:block bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer hover:text-gray-700"
                            wire:click="sort('tanggal')">
                            Tanggal
                            @if ($sortBy === 'tanggal') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer hover:text-gray-700"
                            wire:click="sort('kode_barang')">
                            Kode
                            @if ($sortBy === 'kode_barang') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Bean</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keterangan</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Masuk</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keluar</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Periode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($entries as $entry)
                        <tr class="hover:bg-gray-50 transition-colors {{ $entry->locked ? 'opacity-70' : '' }}">
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                {{ $entry->tanggal->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('stock.detail', $entry->kode_barang) }}"
                                   class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700 hover:bg-amber-100 hover:text-amber-800">
                                    {{ $entry->kode_barang }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-900">{{ $entry->nama_bean }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                    {{ match($entry->keterangan) {
                                        'Production In' => 'bg-green-50 text-green-700',
                                        'Sales Out'     => 'bg-red-50 text-red-600',
                                        default         => 'bg-blue-50 text-blue-700',
                                    } }}">
                                    {{ $entry->keterangan }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-green-600">
                                {{ $entry->masuk > 0 ? number_format($entry->masuk, 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-red-500">
                                {{ $entry->keluar > 0 ? number_format($entry->keluar, 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">
                                {{ number_format($entry->saldo, 2) }}
                                <span class="text-xs font-normal text-gray-400">{{ $entry->satuan }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-mono text-xs text-gray-500">{{ $entry->periode }}</span>
                                @if ($entry->locked)
                                    <span class="ml-1 text-xs text-gray-400" title="Locked">🔒</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-400 text-sm">
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $entries->links() }}
        </div>
    </div>

    {{-- Mobile pagination --}}
    <div class="sm:hidden">
        {{ $entries->links() }}
    </div>
</div>
