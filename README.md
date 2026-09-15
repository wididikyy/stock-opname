# Stockers — Stock Opname Roastery

Aplikasi manajemen stok (stock opname) untuk coffee roastery. Mencatat mutasi masuk/keluar bahan baku green bean & roasted bean, mengelola periode bulanan, dan menghasilkan laporan ringkasan per periode.

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13 (PHP 8.3+) |
| Frontend | Livewire 4.1 + Alpine.js |
| Styling | Tailwind CSS v4 |
| Database | MySQL |
| Starter Kit | Livewire Blaze (`livewire/blaze`) |
| Export | Laravel Excel 4 (`maatwebsite/excel`) |

---

## Fitur

- **Dashboard** — ringkasan saldo stok periode aktif per kode barang dalam bentuk kartu
- **Mutasi Stok** — tabel semua transaksi dengan filter periode, kode barang, dan keterangan; sortable per kolom; export Excel
- **Input Opname** — form tambah transaksi stok baru dengan auto-generate kode barang dan auto-fill data dari kode yang sudah ada
- **Laporan Periode** — ringkasan total masuk, keluar, dan saldo akhir per kode barang; export Excel
- **Tutup Buku** — menutup periode aktif (lock semua transaksi + buat snapshot saldo), membuka periode baru (carry-over saldo sebagai Opening Stock)
- **Responsive** — sidebar desktop, bottom navigation bar di mobile; tampilan tabel berubah jadi kartu di layar kecil

---

## Struktur Proyek

```
app/
├── DataTransferObjects/
│   └── StockSummary.php          # DTO ringkasan stok per kode barang
├── Exports/
│   ├── StockEntriesExport.php    # Export mutasi stok ke Excel
│   └── LaporanPeriodeExport.php  # Export laporan periode ke Excel
├── Livewire/
│   ├── Dashboard.php
│   ├── Opname/StockForm.php
│   ├── Stock/StockTable.php
│   ├── Stock/StockDetail.php
│   ├── Laporan/LaporanPeriode.php
│   └── Periode/PeriodePanel.php
├── Models/
│   ├── StockEntry.php
│   ├── PeriodeLog.php
│   └── StokSnapshot.php
└── Services/
    ├── StockService.php          # CRUD + kalkulasi saldo
    └── PeriodeService.php        # Tutup buku & buka buku

database/migrations/
├── 2026_09_14_000001_create_stock_entries_table.php
├── 2026_09_14_000002_create_periode_logs_table.php
└── 2026_09_14_000003_create_stok_snapshots_table.php

resources/views/
├── layouts/app.blade.php         # Layout utama (sidebar + bottom nav)
└── livewire/
    ├── dashboard.blade.php
    ├── opname/stock-form.blade.php
    ├── stock/stock-table.blade.php
    ├── stock/stock-detail.blade.php
    ├── laporan/laporan-periode.blade.php
    └── periode/periode-panel.blade.php
```

---

## Skema Database

### `stock_entries`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `tanggal` | date | Tanggal transaksi |
| `kode_barang` | varchar | Kode unik barang (misal `RB-001`) |
| `nama_bean` | varchar | Nama bahan baku |
| `jenis` | varchar | Arabica / Robusta / Blend / dll |
| `kategori` | varchar | Green Bean / Roasted Bean / dll |
| `keterangan` | varchar | Opening Stock / Production In / Sales Out / Adjustment |
| `masuk` | decimal(10,2) | Qty masuk |
| `keluar` | decimal(10,2) | Qty keluar |
| `saldo` | decimal(10,2) | Saldo berjalan |
| `satuan` | varchar | kg / pcs / dll |
| `periode` | varchar | Format `YYYY-MM` (misal `2026-09`) |
| `locked` | boolean | True jika periode sudah ditutup |

### `periode_logs`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `periode` | varchar | Unique, format `YYYY-MM` |
| `tanggal_mulai` | date | Tanggal buka periode |
| `tanggal_tutup` | date\|null | Tanggal tutup periode |
| `status` | enum | `Open` / `Closed` |
| `ditutup_oleh` | varchar\|null | Nama/identifier penutup |

### `stok_snapshots`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `periode` | varchar | Periode sumber snapshot |
| `kode_barang` | varchar | Kode barang |
| `nama_bean` | varchar | Nama barang |
| `jenis` | varchar | Jenis barang |
| `kategori` | varchar | Kategori barang |
| `saldo_akhir` | decimal(10,2) | Saldo akhir saat tutup buku |
| `satuan` | varchar | Satuan |

---

## Routes

| Method | URI | Livewire Component | Nama Route |
|---|---|---|---|
| GET | `/` | `Dashboard` | `dashboard` |
| GET | `/stock` | `Stock\StockTable` | `stock.index` |
| GET | `/stock/{kodeBarang}` | `Stock\StockDetail` | `stock.detail` |
| GET | `/opname` | `Opname\StockForm` | `opname.create` |
| GET | `/laporan` | `Laporan\LaporanPeriode` | `laporan.index` |
| GET | `/periode` | `Periode\PeriodePanel` | `periode.index` |

---

## Instalasi

### Prasyarat
- PHP 8.3+
- Composer
- Node.js & npm
- MySQL

> **Laragon (Windows):** Pastikan menggunakan PHP 8.3 CLI secara eksplisit:
> `D:/laragon/bin/php/php-8.3.x-Win32-vs16-x64/php.exe`
> Dan aktifkan `extension=zip` di `php.ini` agar Laravel Excel bisa berjalan.

### Langkah Setup

**1. Clone & install dependencies**
```bash
git clone <repo-url>
cd stock-opname
composer install
npm install
```

**2. Konfigurasi environment**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_NAME=Stockers
DB_DATABASE=stockers
DB_USERNAME=root
DB_PASSWORD=
```

**3. Buat database & jalankan migrasi**
```sql
-- Di MySQL client:
CREATE DATABASE stockers CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
```bash
php artisan migrate
```

**4. Build asset**
```bash
npm run build
```

**5. Jalankan server**
```bash
php artisan serve
```

Buka `http://localhost:8000`.

---

## Alur Penggunaan

```
1. Buka Periode Baru
   └── /periode → "Buka Periode Baru" → isi YYYY-MM + tanggal mulai

2. Input Transaksi
   └── /opname → isi form → Simpan
       ├── Generate kode baru: isi prefix (misal RB) → klik "+ Generate"
       └── Pakai kode lama: ketik/pilih kode → data otomatis terisi

3. Pantau Stok
   ├── /          → Dashboard (ringkasan kartu per barang)
   ├── /stock     → Tabel semua mutasi (filter + sort + export)
   └── /laporan   → Ringkasan per periode (export Excel)

4. Tutup Buku (akhir periode)
   └── /periode → "Tutup Buku" → konfirmasi → periode terkunci
       └── Buka periode baru → saldo carry-over otomatis sebagai Opening Stock
```

---

## Logika Saldo

```
saldo = saldo_terakhir(kode_barang, periode) + masuk - keluar
```

- Saldo dihitung saat `addEntry()` menggunakan `lockForUpdate()` untuk mencegah race condition
- Opening Stock periode baru: `saldo = saldo_akhir snapshot` (masuk=0, keluar=0)
- Setelah tutup buku, semua entri di periode tersebut di-lock (`locked = true`)

---

## Export Excel

Tombol **Export Excel** tersedia di:
- `/stock` — mengekspor semua entri sesuai filter aktif (periode / kode / keterangan)
- `/laporan` — mengekspor ringkasan saldo per kode barang untuk periode yang dipilih

Format file: `.xlsx`, nama file otomatis menyertakan periode/tanggal.

---

## Kode Barang

Format: `{PREFIX}-{NOMOR}` contoh: `RB-001`, `RB-002`, `GB-001`

- **Auto-generate:** isi prefix → klik "+ Generate" → nomor urut berikutnya terisi otomatis
- **Pakai kode lama:** ketik atau pilih dari dropdown → nama bean, jenis, kategori, satuan otomatis terisi

---

## Pengembangan

```bash
# Jalankan dev server (Vite + artisan serve bersamaan)
composer dev

# Build asset production
npm run build

# Lint kode PHP
composer lint

# Static analysis
composer types:check
```
