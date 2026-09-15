<?php

use App\Livewire\Dashboard;
use App\Livewire\Laporan\LaporanPeriode;
use App\Livewire\Opname\StockForm;
use App\Livewire\Periode\PeriodePanel;
use App\Livewire\Stock\StockDetail;
use App\Livewire\Stock\StockTable;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/stock', StockTable::class)->name('stock.index');
Route::get('/stock/{kodeBarang}', StockDetail::class)->name('stock.detail');
Route::get('/opname', StockForm::class)->name('opname.create');
Route::get('/laporan', LaporanPeriode::class)->name('laporan.index');
Route::get('/periode', PeriodePanel::class)->name('periode.index');
