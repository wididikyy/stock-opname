<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kode_barang', 50)->index();
            $table->string('nama_bean', 100);
            $table->string('jenis', 50);
            $table->string('kategori', 50);
            $table->string('keterangan', 50);
            $table->decimal('keluar', 10, 2)->default(0);
            $table->decimal('masuk', 10, 2)->default(0);
            $table->decimal('saldo', 10, 2)->default(0);
            $table->string('satuan', 20);
            $table->string('periode', 7)->index();
            $table->boolean('locked')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
    }
};
