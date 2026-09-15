<div class="p-4 sm:p-8 max-w-2xl space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Input Opname</h1>
        <p class="text-sm text-gray-500 mt-0.5">Tambah transaksi stok baru</p>
    </div>

    {{-- Active periode info --}}
    @if ($activePeriode)
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            Periode aktif: <strong>{{ $activePeriode }}</strong>. Data akan masuk ke periode ini.
        </div>
    @else
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Tidak ada periode aktif. Buka periode baru di halaman
            <a href="{{ route('periode.index') }}" class="font-semibold underline">Tutup Buku</a> terlebih dahulu.
        </div>
    @endif

    {{-- Flash --}}
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="bg-white rounded-xl border border-gray-200 shadow-xs p-4 sm:p-6 space-y-5">

        {{-- Tanggal --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" wire:model="tanggal"
                   class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                          {{ $errors->has('tanggal') ? 'border-red-400' : 'border-gray-200' }}">
            @error('tanggal')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kode Barang --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
            <div class="flex flex-col sm:flex-row gap-2">
                {{-- Prefix --}}
                <div class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 sm:shrink-0">
                    <span class="text-xs text-gray-400 font-medium">Prefix</span>
                    <input type="text" wire:model="kodePrefix" maxlength="10" placeholder="RB"
                           class="flex-1 sm:w-14 bg-transparent text-sm font-mono font-bold text-gray-700 focus:outline-none uppercase">
                </div>
                {{-- Kode + Generate --}}
                <div class="flex flex-1">
                    <input type="text" wire:model="kode_barang" wire:change="fillFromKode"
                           list="kode-options" placeholder="Pilih atau ketik kode…"
                           class="flex-1 min-w-0 border rounded-l-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-amber-300
                                  {{ $errors->has('kode_barang') ? 'border-red-400' : 'border-gray-200' }}">
                    <button type="button" wire:click="generateKodeBarang"
                            class="px-3 py-2 bg-amber-100 border border-l-0 border-amber-200 rounded-r-lg text-amber-700 hover:bg-amber-200 text-xs font-semibold transition-colors whitespace-nowrap">
                        + Generate
                    </button>
                </div>
            </div>
            <datalist id="kode-options">
                @foreach ($kodeOptions as $opt)
                    <option value="{{ $opt['kode_barang'] }}">{{ $opt['nama_bean'] }}</option>
                @endforeach
            </datalist>
            @error('kode_barang')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-400">
                Klik <strong>+ Generate</strong> untuk kode baru otomatis, atau pilih/ketik kode yang sudah ada.
            </p>
        </div>

        {{-- Nama Bean & Jenis --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bean</label>
                <input type="text" wire:model="nama_bean" placeholder="Contoh: House Blend"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                              {{ $errors->has('nama_bean') ? 'border-red-400' : 'border-gray-200' }}">
                @error('nama_bean')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                <input type="text" wire:model="jenis" placeholder="Arabica / Blend / …" list="jenis-options"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                              {{ $errors->has('jenis') ? 'border-red-400' : 'border-gray-200' }}">
                <datalist id="jenis-options">
                    <option>Arabica</option>
                    <option>Robusta</option>
                    <option>Blend</option>
                    <option>Liberica</option>
                </datalist>
                @error('jenis')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Kategori & Keterangan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <input type="text" wire:model="kategori" placeholder="Roasted Bean"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                              {{ $errors->has('kategori') ? 'border-red-400' : 'border-gray-200' }}">
                @error('kategori')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <select wire:model="keterangan"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                               {{ $errors->has('keterangan') ? 'border-red-400' : 'border-gray-200' }}">
                    <option value="">— Pilih —</option>
                    <option value="Opening Stock">Opening Stock</option>
                    <option value="Production In">Production In</option>
                    <option value="Sales Out">Sales Out</option>
                    <option value="Adjustment">Adjustment</option>
                </select>
                @error('keterangan')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Masuk, Keluar, Satuan --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Masuk</label>
                <input type="number" step="0.01" min="0" wire:model="masuk"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                              {{ $errors->has('masuk') ? 'border-red-400' : 'border-gray-200' }}">
                @error('masuk')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keluar</label>
                <input type="number" step="0.01" min="0" wire:model="keluar"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                              {{ $errors->has('keluar') ? 'border-red-400' : 'border-gray-200' }}">
                @error('keluar')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" wire:model="satuan" placeholder="kg"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300
                              {{ $errors->has('satuan') ? 'border-red-400' : 'border-gray-200' }}">
                @error('satuan')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row gap-3">
            <button type="submit"
                    class="w-full sm:w-auto px-5 py-3 sm:py-2.5 bg-amber-700 text-white text-sm font-medium rounded-lg hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-400 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ $activePeriode ? '' : 'disabled' }}>
                <span wire:loading.remove>Simpan</span>
                <span wire:loading>Menyimpan…</span>
            </button>
            <button type="button" wire:click="$refresh"
                    class="w-full sm:w-auto px-5 py-3 sm:py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                Reset
            </button>
        </div>
    </form>
</div>
