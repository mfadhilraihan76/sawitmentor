<?php

namespace App\Http\Controllers;

use App\Models\HargaTbs;
use App\Models\Panen;
use App\Models\Petani;
use Illuminate\Support\Facades\DB;

class PetaniDashboardController extends Controller
{
    /**
     * Dashboard Petani.
     * Sementara petani dipilih lewat URL: /petani/{id}/dashboard
     * Setelah login dipasang, ganti parameter dengan:
     *   $petani = Petani::findOrFail(auth()->user()->petani_id);
     */
    public function index(Petani $petani)
    {
        $kebunIds = $petani->kebun()->pluck('id');

        $totalPanen      = Panen::whereIn('kebun_id', $kebunIds)->sum('berat_kg');
        $totalPendapatan = Panen::whereIn('kebun_id', $kebunIds)->sum('total_pendapatan');
        $jumlahKebun     = $kebunIds->count();

        // Panen bulan ini
        $panenBulanIni = Panen::whereIn('kebun_id', $kebunIds)
            ->whereYear('tanggal_panen', now()->year)
            ->whereMonth('tanggal_panen', now()->month)
            ->sum('berat_kg');

        // Harga TBS yang berlaku hari ini
        $hargaSekarang = HargaTbs::whereDate('periode_mulai', '<=', now())
            ->whereDate('periode_selesai', '>=', now())
            ->first();

        // Grafik panen per bulan (kg)
        $perBulan = Panen::select(
                DB::raw("DATE_FORMAT(tanggal_panen, '%Y-%m') as bulan"),
                DB::raw('SUM(berat_kg) as total')
            )
            ->whereIn('kebun_id', $kebunIds)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->limit(12)
            ->get();

        // Kebun milik petani + total panen dan pendapatan per kebun
        $kebun = $petani->kebun()
            ->withSum('panen', 'berat_kg')
            ->withSum('panen', 'total_pendapatan')
            ->get();

        $riwayat = Panen::with(['kebun', 'hargaTbs'])
            ->whereIn('kebun_id', $kebunIds)
            ->latest('tanggal_panen')
            ->take(10)
            ->get();

        return view('petani_area.dashboard', compact(
            'petani', 'totalPanen', 'totalPendapatan', 'jumlahKebun',
            'panenBulanIni', 'hargaSekarang', 'perBulan', 'kebun', 'riwayat'
        ));
    }
}