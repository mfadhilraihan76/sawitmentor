<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Petani <-> akun login
        if (! Schema::hasColumn('petani', 'user_id')) {
            Schema::table('petani', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            });
        }

        // 2) Panen: petani_id + harga per kg (disimpan saat panen dicatat)
        Schema::table('panen', function (Blueprint $table) {
            if (! Schema::hasColumn('panen', 'petani_id')) {
                $table->unsignedBigInteger('petani_id')->nullable()->index();
            }
            if (! Schema::hasColumn('panen', 'harga_per_kg')) {
                $table->decimal('harga_per_kg', 12, 2)->nullable();
            }
            // jaga-jaga kalau tabel panen masih berupa kerangka kosong
            if (! Schema::hasColumn('panen', 'kebun_id')) {
                $table->unsignedBigInteger('kebun_id')->nullable()->index();
            }
            if (! Schema::hasColumn('panen', 'harga_tbs_id')) {
                $table->unsignedBigInteger('harga_tbs_id')->nullable()->index();
            }
            if (! Schema::hasColumn('panen', 'tanggal_panen')) {
                $table->date('tanggal_panen')->nullable();
            }
            if (! Schema::hasColumn('panen', 'berat_kg')) {
                $table->decimal('berat_kg', 10, 2)->default(0);
            }
            if (! Schema::hasColumn('panen', 'total_pendapatan')) {
                $table->decimal('total_pendapatan', 14, 2)->default(0);
            }
        });

        // isi petani_id dan harga_per_kg untuk panen yang sudah ada
        foreach (DB::table('kebun')->get(['id', 'petani_id']) as $k) {
            DB::table('panen')->where('kebun_id', $k->id)->whereNull('petani_id')
                ->update(['petani_id' => $k->petani_id]);
        }
        foreach (DB::table('harga_tbs')->get(['id', 'harga_per_kg']) as $h) {
            DB::table('panen')->where('harga_tbs_id', $h->id)->whereNull('harga_per_kg')
                ->update(['harga_per_kg' => $h->harga_per_kg]);
        }

        // 3) Keuangan
        if (! Schema::hasTable('keuangan')) {
            Schema::create('keuangan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('petani_id')->index();
                $table->unsignedBigInteger('kebun_id')->nullable()->index();
                $table->date('tanggal');
                $table->string('jenis', 15);
                $table->string('kategori', 60);
                $table->decimal('jumlah', 14, 2);
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        // 4) Perawatan
        if (! Schema::hasTable('perawatan')) {
            Schema::create('perawatan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('petani_id')->index();
                $table->unsignedBigInteger('kebun_id')->nullable()->index();
                $table->string('jenis', 60);
                $table->date('tanggal');
                $table->string('status', 15)->default('terjadwal');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // 5) Laporan
        if (! Schema::hasTable('laporan')) {
            Schema::create('laporan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('petani_id')->index();
                $table->string('judul', 150);
                $table->date('dari');
                $table->date('sampai');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
        Schema::dropIfExists('perawatan');
        Schema::dropIfExists('keuangan');

        if (Schema::hasColumn('panen', 'harga_per_kg')) {
            Schema::table('panen', fn (Blueprint $t) => $t->dropColumn('harga_per_kg'));
        }
        if (Schema::hasColumn('panen', 'petani_id')) {
            Schema::table('panen', fn (Blueprint $t) => $t->dropColumn('petani_id'));
        }
        if (Schema::hasColumn('petani', 'user_id')) {
            Schema::table('petani', fn (Blueprint $t) => $t->dropColumn('user_id'));
        }
    }
};