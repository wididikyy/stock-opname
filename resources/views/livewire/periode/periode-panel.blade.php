<div class="p-4 sm:p-8 space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tutup Buku</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola periode aktif dan riwayat tutup buku</p>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Current period status --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-xs p-6 space-y-4">
            <h2 class="font-semibold text-gray-700">Status Periode Aktif</h2>

            @if ($activePeriode)
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                    <div>
                        <p class="font-bold text-xl text-gray-900">{{ $activePeriode->periode }}</p>
                        <p class="text-xs text-gray-500">
                            Mulai {{ $activePeriode->tanggal_mulai->format('d M Y') }}
                        </p>
                    </div>
                </div>

                @if (! $confirmingTutup)
                    <button wire:click="confirmTutupBuku"
                            class="w-full mt-2 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 transition-colors">
                        Tutup Buku Periode {{ $activePeriode->periode }}
                    </button>
                @else
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 space-y-3">
                        <p class="text-sm font-semibold text-red-800">Konfirmasi Tutup Buku</p>
                        <p class="text-xs text-red-700">
                            Aksi ini akan mengunci semua transaksi periode <strong>{{ $activePeriode->periode }}</strong>
                            dan tidak dapat dibatalkan. Preview saldo akhir:
                        </p>

                        @if (count($previewSummary) > 0)
                            <div class="divide-y divide-red-200 text-xs">
                                @foreach ($previewSummary as $item)
                                    <div class="flex justify-between py-1">
                                        <span class="text-red-800 font-medium">{{ $item->namaBean }}</span>
                                        <span class="text-red-900 font-bold">{{ number_format($item->saldoAkhir, 2) }} {{ $item->satuan }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-red-600 italic">Tidak ada data pada periode ini.</p>
                        @endif

                        <div class="flex gap-2 pt-1">
                            <button wire:click="tutupBuku" wire:loading.attr="disabled"
                                    class="flex-1 px-3 py-2 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                <span wire:loading.remove wire:target="tutupBuku">Ya, Tutup Buku</span>
                                <span wire:loading wire:target="tutupBuku">Memproses…</span>
                            </button>
                            <button wire:click="cancelTutupBuku"
                                    class="flex-1 px-3 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-sm text-gray-500 italic">Tidak ada periode aktif saat ini.</div>
            @endif
        </div>

        {{-- Buka Buku --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-xs p-6 space-y-4">
            <h2 class="font-semibold text-gray-700">Buka Periode Baru</h2>

            @if ($activePeriode && ! $showBukaBuku)
                <p class="text-sm text-gray-500">
                    Tutup periode aktif (<strong>{{ $activePeriode->periode }}</strong>) terlebih dahulu sebelum membuka periode baru.
                </p>
            @else
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode Baru (YYYY-MM)</label>
                        <input type="text" wire:model="periodeBaru" placeholder="2026-10"
                               class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-amber-300
                                      {{ $errors->has('periodeBaru') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('periodeBaru')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" wire:model="tanggalMulai"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                                      {{ $errors->has('tanggalMulai') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('tanggalMulai')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button wire:click="bukaBuku" wire:loading.attr="disabled"
                            class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors">
                        <span wire:loading.remove wire:target="bukaBuku">Buka Periode {{ $periodeBaru }}</span>
                        <span wire:loading wire:target="bukaBuku">Memproses…</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Riwayat Periode --}}
    <div class="space-y-3">
        <h2 class="font-semibold text-gray-700 px-1">Riwayat Periode</h2>

        {{-- Mobile: cards --}}
        <div class="sm:hidden space-y-3">
            @forelse ($history as $log)
                <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono font-bold text-gray-900">{{ $log->periode }}</span>
                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $log->status === 'Open' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $log->status }}
                        </span>
                    </div>
                    <div class="flex gap-4 text-xs text-gray-500">
                        <span>Mulai: <span class="text-gray-700">{{ $log->tanggal_mulai->format('d M Y') }}</span></span>
                        <span>Tutup: <span class="text-gray-700">{{ $log->tanggal_tutup ? $log->tanggal_tutup->format('d M Y') : '—' }}</span></span>
                    </div>
                    @if ($log->ditutup_oleh)
                        <p class="text-xs text-gray-400">Oleh: {{ $log->ditutup_oleh }}</p>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 py-8 text-center text-gray-400 text-sm">
                    Belum ada riwayat periode.
                </div>
            @endforelse
        </div>

        {{-- Desktop: table --}}
        <div class="hidden sm:block bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Periode</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Mulai</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Tutup</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Ditutup Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($history as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-mono font-bold text-gray-900">{{ $log->periode }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $log->tanggal_mulai->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $log->tanggal_tutup ? $log->tanggal_tutup->format('d M Y') : '—' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-medium
                                        {{ $log->status === 'Open' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $log->ditutup_oleh ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                                    Belum ada riwayat periode.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
