<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('periode', 7)->index();
            $table->string('kode_barang', 50);
            $table->string('nama_bean', 100);
            $table->string('jenis', 50)->default('');
            $table->string('kategori', 50)->default('');
            $table->decimal('saldo_akhir', 10, 2);
            $table->string('satuan', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_snapshots');
    }
};
