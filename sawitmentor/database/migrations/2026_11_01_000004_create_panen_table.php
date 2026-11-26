<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kebun_id')->constrained('kebun')->restrictOnDelete();
            $table->foreignId('harga_tbs_id')->constrained('harga_tbs')->restrictOnDelete();
            $table->date('tanggal_panen');
            $table->decimal('berat_kg', 10, 2);
            $table->decimal('total_pendapatan', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panen');
    }
};