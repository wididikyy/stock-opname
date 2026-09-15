<div class="p-4 sm:p-8 space-y-8 max-w-4xl">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Panduan Penggunaan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Dokumentasi lengkap sistem stock opname Stockers</p>
    </div>

    {{-- Daftar Isi --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
        <p class="text-xs font-semibold text-amber-800 uppercase tracking-wide mb-3">Daftar Isi</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-sm">
            @foreach ([
                ['#apa-itu',    'Apa itu Stockers?'],
                ['#alur',       'Alur Penggunaan'],
                ['#periode',    'Buka & Tutup Periode'],
                ['#opname',     'Input Opname'],
                ['#mutasi',     'Mutasi Stok'],
                ['#laporan',    'Laporan Periode'],
                ['#kode',       'Kode Barang'],
                ['#export',     'Export Excel'],
                ['#catatan',    'Catatan Penting'],
            ] as [$href, $label])
                <a href="{{ $href }}" class="flex items-center gap-2 text-amber-700 hover:text-amber-900 hover:underline">
                    <span class="text-amber-400">›</span> {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- 1. Apa itu Stockers --}}
    <section id="apa-itu" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">1</span>
            <h2 class="text-lg font-bold text-gray-900">Apa itu Stockers?</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3 text-sm text-gray-700 leading-relaxed">
            <p>
                <strong>Stockers</strong> adalah sistem pencatatan stok (stock opname) untuk roastery kopi.
                Sistem ini membantu mencatat setiap pergerakan bahan baku — baik yang masuk maupun keluar —
                dan memantau saldo stok secara real-time per periode bulanan.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                <div class="rounded-lg bg-green-50 border border-green-100 p-3 text-center">
                    <p class="text-2xl font-bold text-green-600">Masuk</p>
                    <p class="text-xs text-green-700 mt-0.5">Production In, Opening Stock</p>
                </div>
                <div class="rounded-lg bg-red-50 border border-red-100 p-3 text-center">
                    <p class="text-2xl font-bold text-red-500">Keluar</p>
                    <p class="text-xs text-red-600 mt-0.5">Sales Out</p>
                </div>
                <div class="rounded-lg bg-blue-50 border border-blue-100 p-3 text-center">
                    <p class="text-2xl font-bold text-blue-600">Saldo</p>
                    <p class="text-xs text-blue-700 mt-0.5">Masuk – Keluar</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Alur Penggunaan --}}
    <section id="alur" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">2</span>
            <h2 class="text-lg font-bold text-gray-900">Alur Penggunaan</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            @foreach ([
                ['Buka Periode',    'Buka periode baru sebelum mulai input (misal: 2026-09)', 'bg-amber-100 text-amber-800'],
                ['Input Opname',    'Catat setiap transaksi masuk / keluar bahan baku',       'bg-amber-100 text-amber-800'],
                ['Pantau Stok',     'Cek Dashboard atau halaman Mutasi Stok untuk saldo terkini', 'bg-amber-100 text-amber-800'],
                ['Buat Laporan',    'Buka halaman Laporan untuk melihat ringkasan per kode barang', 'bg-amber-100 text-amber-800'],
                ['Tutup Buku',      'Di akhir periode, tutup buku — saldo carry-over otomatis ke periode berikutnya', 'bg-red-100 text-red-700'],
            ] as $i => [$step, $desc, $badge])
                <div class="flex items-start gap-4 px-5 py-4 {{ $i > 0 ? 'border-t border-gray-100' : '' }}">
                    <span class="flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-600 text-xs font-bold shrink-0 mt-0.5">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <span class="inline-block text-xs px-2 py-0.5 rounded-full font-semibold {{ $badge }} mb-1">{{ $step }}</span>
                        <p class="text-sm text-gray-600">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 3. Buka & Tutup Periode --}}
    <section id="periode" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">3</span>
            <h2 class="text-lg font-bold text-gray-900">Buka & Tutup Periode</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 text-sm">

            <div class="p-5 space-y-3">
                <p class="font-semibold text-gray-800">Buka Periode Baru</p>
                <ol class="list-decimal list-inside space-y-1.5 text-gray-600">
                    <li>Buka halaman <a href="{{ route('periode.index') }}" class="text-amber-700 underline font-medium">Tutup Buku</a></li>
                    <li>Di bagian <strong>Buka Periode Baru</strong>, isi format periode: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">YYYY-MM</code> (contoh: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">2026-10</code>)</li>
                    <li>Isi tanggal mulai, lalu klik <strong>Buka Periode</strong></li>
                    <li>Periode aktif akan muncul di status bar dan siap digunakan untuk input</li>
                </ol>
                <div class="rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 text-xs text-blue-800">
                    Periode hanya bisa dibuka jika tidak ada periode lain yang masih <strong>Open</strong>. Tutup periode aktif terlebih dahulu.
                </div>
            </div>

            <div class="p-5 space-y-3">
                <p class="font-semibold text-gray-800">Tutup Buku</p>
                <ol class="list-decimal list-inside space-y-1.5 text-gray-600">
                    <li>Buka halaman <a href="{{ route('periode.index') }}" class="text-amber-700 underline font-medium">Tutup Buku</a></li>
                    <li>Klik tombol <strong>Tutup Buku Periode …</strong></li>
                    <li>Preview saldo akhir tiap kode barang akan ditampilkan untuk konfirmasi</li>
                    <li>Klik <strong>Ya, Tutup Buku</strong> — semua transaksi periode tersebut dikunci</li>
                </ol>
                <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-xs text-red-800">
                    <strong>Perhatian:</strong> Tutup buku tidak dapat dibatalkan. Semua entri akan terkunci (ditandai <span class="font-mono">🔒</span>) dan saldo akhir disimpan sebagai snapshot.
                </div>
                <div class="rounded-lg bg-green-50 border border-green-100 px-4 py-3 text-xs text-green-800">
                    Setelah tutup buku, saldo akhir setiap kode barang otomatis terbawa sebagai <strong>Opening Stock</strong> di periode berikutnya saat buka buku.
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Input Opname --}}
    <section id="opname" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">4</span>
            <h2 class="text-lg font-bold text-gray-900">Input Opname</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 text-sm">
            <p class="text-gray-600">Halaman <a href="{{ route('opname.create') }}" class="text-amber-700 underline font-medium">Input Opname</a> digunakan untuk mencatat setiap transaksi stok.</p>

            <div class="space-y-3">
                <p class="font-semibold text-gray-800">Field yang perlu diisi:</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left px-3 py-2 border border-gray-200 font-semibold text-gray-600">Field</th>
                                <th class="text-left px-3 py-2 border border-gray-200 font-semibold text-gray-600">Keterangan</th>
                                <th class="text-left px-3 py-2 border border-gray-200 font-semibold text-gray-600">Contoh</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @foreach ([
                                ['Tanggal',    'Tanggal transaksi terjadi',                         '2026-09-15'],
                                ['Kode Barang','Kode unik barang — bisa generate otomatis',         'RB-001'],
                                ['Nama Bean',  'Nama bahan baku',                                   'Ethiopia Yirgacheffe'],
                                ['Jenis',      'Jenis biji kopi',                                   'Arabica'],
                                ['Kategori',   'Kategori produk',                                   'Green Bean'],
                                ['Keterangan', 'Jenis transaksi',                                   'Production In'],
                                ['Masuk',      'Jumlah qty masuk (0 jika transaksi keluar)',         '50'],
                                ['Keluar',     'Jumlah qty keluar (0 jika transaksi masuk)',         '0'],
                                ['Satuan',     'Satuan ukuran',                                     'kg'],
                            ] as [$field, $ket, $contoh])
                                <tr class="border-b border-gray-100">
                                    <td class="px-3 py-2 border border-gray-200 font-mono font-medium">{{ $field }}</td>
                                    <td class="px-3 py-2 border border-gray-200">{{ $ket }}</td>
                                    <td class="px-3 py-2 border border-gray-200 font-mono text-gray-500">{{ $contoh }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-xs text-amber-800 space-y-1">
                <p class="font-semibold">Tips Input Cepat:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <li>Jika menginput barang yang sudah ada, ketik/pilih kode lama — nama, jenis, kategori, dan satuan otomatis terisi</li>
                    <li>Transaksi masuk: isi field <strong>Masuk</strong>, biarkan Keluar = 0</li>
                    <li>Transaksi keluar: isi field <strong>Keluar</strong>, biarkan Masuk = 0</li>
                    <li>Saldo dihitung otomatis oleh sistem</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- 5. Mutasi Stok --}}
    <section id="mutasi" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">5</span>
            <h2 class="text-lg font-bold text-gray-900">Mutasi Stok</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3 text-sm text-gray-600">
            <p>Halaman <a href="{{ route('stock.index') }}" class="text-amber-700 underline font-medium">Mutasi Stok</a> menampilkan semua histori transaksi.</p>
            <ul class="space-y-2">
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span><strong>Filter</strong> — saring berdasarkan periode, kode barang, atau jenis keterangan transaksi</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span><strong>Sort</strong> — klik header kolom Tanggal atau Kode untuk mengurutkan data</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span><strong>Detail barang</strong> — klik chip kode barang untuk melihat histori lengkap satu barang</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span><strong>Export</strong> — klik tombol <em>Export Excel</em> untuk mengunduh data sesuai filter aktif</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span>Baris bertanda <span class="font-mono text-xs">🔒</span> artinya transaksi dari periode yang sudah ditutup dan tidak dapat diubah</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- 6. Laporan Periode --}}
    <section id="laporan" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">6</span>
            <h2 class="text-lg font-bold text-gray-900">Laporan Periode</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3 text-sm text-gray-600">
            <p>Halaman <a href="{{ route('laporan.index') }}" class="text-amber-700 underline font-medium">Laporan</a> menampilkan ringkasan stok per kode barang untuk periode yang dipilih.</p>
            <ul class="space-y-2">
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span>Pilih periode dari dropdown untuk memuat data</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span>3 kartu di atas menampilkan <strong>Total Item</strong>, <strong>Total Masuk</strong>, dan <strong>Total Keluar</strong> periode tersebut</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span>Tabel detail menampilkan saldo akhir tiap kode barang</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5 shrink-0">▸</span>
                    <span>Klik <strong>Export Excel</strong> untuk mengunduh laporan periode ke file <code class="bg-gray-100 px-1 rounded">.xlsx</code></span>
                </li>
            </ul>
        </div>
    </section>

    {{-- 7. Kode Barang --}}
    <section id="kode" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">7</span>
            <h2 class="text-lg font-bold text-gray-900">Kode Barang</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 text-sm text-gray-600">
            <p>Kode barang mengidentifikasi setiap jenis bahan baku secara unik. Format: <code class="bg-gray-100 px-2 py-0.5 rounded font-mono text-xs font-bold">PREFIX-NNN</code></p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4 space-y-2">
                    <p class="font-semibold text-gray-700 text-xs uppercase tracking-wide">Barang Baru</p>
                    <ol class="list-decimal list-inside space-y-1 text-xs">
                        <li>Isi prefix (contoh: <code class="font-mono">RB</code> untuk Roasted Bean)</li>
                        <li>Klik tombol <strong>+ Generate</strong></li>
                        <li>Kode otomatis terisi: <code class="font-mono">RB-001</code>, <code class="font-mono">RB-002</code>, dst.</li>
                    </ol>
                </div>
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4 space-y-2">
                    <p class="font-semibold text-gray-700 text-xs uppercase tracking-wide">Barang Lama</p>
                    <ol class="list-decimal list-inside space-y-1 text-xs">
                        <li>Ketik atau pilih kode dari dropdown</li>
                        <li>Nama, jenis, kategori, dan satuan otomatis terisi</li>
                        <li>Langsung isi qty masuk/keluar dan simpan</li>
                    </ol>
                </div>
            </div>
            <div class="rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 text-xs text-blue-800">
                Gunakan prefix yang konsisten: <code class="font-mono">GB</code> untuk Green Bean, <code class="font-mono">RB</code> untuk Roasted Bean, dsb. Ini memudahkan filtering dan pelaporan.
            </div>
        </div>
    </section>

    {{-- 8. Export Excel --}}
    <section id="export" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">8</span>
            <h2 class="text-lg font-bold text-gray-900">Export Excel</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3 text-sm text-gray-600">
            <p>Data bisa diekspor ke file <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">.xlsx</code> dari dua halaman:</p>
            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left px-3 py-2 border border-gray-200 font-semibold text-gray-600">Halaman</th>
                            <th class="text-left px-3 py-2 border border-gray-200 font-semibold text-gray-600">Isi File</th>
                            <th class="text-left px-3 py-2 border border-gray-200 font-semibold text-gray-600">Nama File</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        <tr>
                            <td class="px-3 py-2 border border-gray-200 font-medium">Mutasi Stok</td>
                            <td class="px-3 py-2 border border-gray-200">Semua transaksi sesuai filter aktif (periode, kode, keterangan)</td>
                            <td class="px-3 py-2 border border-gray-200 font-mono text-gray-500">mutasi-stok-YYYY-MM.xlsx</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-3 py-2 border border-gray-200 font-medium">Laporan Periode</td>
                            <td class="px-3 py-2 border border-gray-200">Ringkasan saldo akhir per kode barang untuk periode terpilih</td>
                            <td class="px-3 py-2 border border-gray-200 font-mono text-gray-500">laporan-YYYY-MM.xlsx</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 9. Catatan Penting --}}
    <section id="catatan" class="scroll-mt-20 space-y-4">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shrink-0">9</span>
            <h2 class="text-lg font-bold text-gray-900">Catatan Penting</h2>
        </div>
        <div class="space-y-3">
            @foreach ([
                ['bg-red-50 border-red-200 text-red-800',     'Tutup buku tidak bisa dibatalkan. Pastikan semua transaksi periode sudah benar sebelum menutup.'],
                ['bg-amber-50 border-amber-200 text-amber-800','Selalu pastikan ada periode aktif (Open) sebelum input opname. Transaksi tidak bisa disimpan jika tidak ada periode aktif.'],
                ['bg-blue-50 border-blue-100 text-blue-800',  'Saldo dihitung otomatis: Saldo = Saldo Sebelumnya + Masuk − Keluar. Jangan memanipulasi field Saldo secara manual.'],
                ['bg-green-50 border-green-100 text-green-800','Opening Stock di periode baru dibuat otomatis dari saldo akhir periode sebelumnya saat buka buku. Tidak perlu input manual.'],
            ] as [$cls, $msg])
                <div class="rounded-lg border px-4 py-3 text-sm {{ $cls }}">
                    {{ $msg }}
                </div>
            @endforeach
        </div>
    </section>

    {{-- Footer --}}
    <div class="border-t border-gray-200 pt-6 text-center">
        <p class="text-xs text-gray-400">Stockers — Stock Opname Roastery &middot; {{ now()->format('Y') }}</p>
    </div>

</div>
