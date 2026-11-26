<?php

namespace Database\Seeders;

use App\Models\HargaTbs;
use App\Models\Kebun;
use App\Models\Panen;
use App\Models\Petani;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $p1 = Petani::create(['nama' => 'Budi Santoso', 'alamat' => 'Jl. Garuda Sakti, Pekanbaru', 'no_hp' => '081234567890']);
        $p2 = Petani::create(['nama' => 'Siti Aminah', 'alamat' => 'Desa Kualu, Kampar', 'no_hp' => '082198765432']);

        $k1 = Kebun::create(['petani_id' => $p1->id, 'nama_kebun' => 'Kebun Sawit Makmur', 'lokasi' => 'Tambang, Kampar', 'luas_lahan' => 4.5]);
        Kebun::create(['petani_id' => $p2->id, 'nama_kebun' => 'Kebun Harapan', 'lokasi' => 'Kualu, Kampar', 'luas_lahan' => 2.0]);

        $h = HargaTbs::create(['periode_mulai' => '2026-11-01', 'periode_selesai' => '2026-11-30', 'harga_per_kg' => 2500]);

        // Contoh dari proposal: 1.250 kg x Rp2.500 = Rp3.125.000
        Panen::create([
            'kebun_id' => $k1->id, 'harga_tbs_id' => $h->id,
            'tanggal_panen' => '2026-11-10', 'berat_kg' => 1250, 'total_pendapatan' => 1250 * 2500,
        ]);
    }
}