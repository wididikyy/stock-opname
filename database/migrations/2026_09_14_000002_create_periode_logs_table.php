<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_logs', function (Blueprint $table) {
            $table->id();
            $table->string('periode', 7)->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_tutup')->nullable();
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->string('ditutup_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_logs');
    }
};
