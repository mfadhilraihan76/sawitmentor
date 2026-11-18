<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kebun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('petani')->restrictOnDelete();
            $table->string('nama_kebun');
            $table->string('lokasi');
            $table->decimal('luas_lahan', 8, 2)->comment('dalam hektar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kebun');
    }
};