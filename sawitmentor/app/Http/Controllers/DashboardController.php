<?php

namespace App\Http\Controllers;

use App\Models\Kebun;
use App\Models\Panen;
use App\Models\Petani;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPanen      = Panen::sum('berat_kg');
        $totalPendapatan = Panen::sum('total_pendapatan');
        $jumlahPetani    = Petani::count();
        $jumlahKebun     = Kebun::count();

        // Kompatibel SQLite dan MySQL
        $formatBulan = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', tanggal_panen)"
            : "DATE_FORMAT(tanggal_panen, '%Y-%m')";

        // 12 bulan terakhir, urut dari yang terlama ke terbaru
        $perBulan = Panen::select(
                DB::raw("$formatBulan as bulan"),
                DB::raw('SUM(berat_kg) as total')
            )
            ->whereNotNull('tanggal_panen')
            ->groupBy('bulan')
            ->orderByDesc('bulan')
            ->limit(12)
            ->get()
            ->reverse()
            ->values();

        $panenTerbaru = Panen::with('kebun.petani')
            ->latest('tanggal_panen')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalPanen',
            'totalPendapatan',
            'jumlahPetani',
            'jumlahKebun',
            'perBulan',
            'panenTerbaru'
        ));
    }
}