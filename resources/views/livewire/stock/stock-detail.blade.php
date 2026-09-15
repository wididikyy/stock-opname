<div class="p-4 sm:p-8 space-y-5">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('stock.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    @if ($bean)
        <div class="bg-white rounded-xl border border-gray-200 p-5 sm:p-6 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-sm bg-gray-100 px-3 py-1 rounded-lg text-gray-600">{{ $bean->kode_barang }}</span>
                        <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-700 font-medium">{{ $bean->jenis }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $bean->nama_bean }}</h1>
                    <p class="text-sm text-gray-500">{{ $bean->kategori }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Saldo terkini</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ number_format((float) $entries->last()?->saldo ?? 0, 2) }}
                        <span class="text-base font-normal text-gray-500">{{ $bean->satuan }}</span>
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">
            Kode barang <strong>{{ $kodeBarang }}</strong> tidak ditemukan.
        </div>
    @endif

    {{-- Histori --}}
    @if ($entries->isNotEmpty())
        <div class="space-y-3">
            <h2 class="font-semibold text-gray-700 px-1">Histori Transaksi</h2>

            {{-- Mobile: cards --}}
            <div class="sm:hidden space-y-3">
                @foreach ($entries as $entry)
                    <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3
                                {{ $entry->locked ? 'opacity-75 bg-gray-50' : '' }}">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs text-gray-400">{{ $entry->tanggal->format('d M Y') }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="font-mono text-xs text-gray-500">{{ $entry->periode }}</span>
                                @if ($entry->locked)
                                    <span class="text-xs" title="Locked">🔒</span>
                                @endif
                            </div>
                        </div>

                        <span class="inline-flex text-xs px-2 py-0.5 rounded-full font-medium
                            {{ match($entry->keterangan) {
                                'Production In' => 'bg-green-50 text-green-700',
                                'Sales Out'     => 'bg-red-50 text-red-600',
                                default         => 'bg-blue-50 text-blue-700',
                            } }}">
                            {{ $entry->keterangan }}
                        </span>

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
                                {{ number_format((float) $entry->saldo, 2) }}
                                <span class="text-xs font-normal text-gray-400">{{ $entry->satuan }}</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop: table --}}
            <div class="hidden sm:block bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keterangan</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Masuk</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keluar</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Periode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($entries as $entry)
                                <tr class="{{ $entry->locked ? 'bg-gray-50 opacity-75' : 'hover:bg-gray-50' }} transition-colors">
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                        {{ $entry->tanggal->format('d M Y') }}
                                    </td>
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
                                        {{ number_format((float) $entry->saldo, 2) }}
                                        <span class="text-xs font-normal text-gray-400">{{ $entry->satuan }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-xs text-gray-500">
                                        {{ $entry->periode }}
                                        @if ($entry->locked) <span title="Locked">🔒</span> @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
