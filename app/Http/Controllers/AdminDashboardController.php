<?php

namespace App\Http\Controllers;

use App\Models\Kebun;
use App\Models\Panen;
use App\Models\Petani;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pt = (new Panen)->getTable();
        $pilih = fn(array $opsi) => collect($opsi)->first(fn($k) => Schema::hasColumn($pt, $k));

        $berat = $pilih(['berat_kg', 'berat', 'total_berat', 'jumlah_kg']);
        $uang = $pilih(['total_pendapatan', 'pendapatan', 'total_harga', 'total']);
        $tgl = $pilih(['tanggal_panen', 'tanggal', 'created_at']);

        $totalPanen = $berat ? Panen::sum($berat) : 0;
        $totalPendapatan = $uang ? Panen::sum($uang) : 0;
        $jumlahPetani = Petani::count();
        $jumlahKebun = Kebun::count();

        $grafik = collect();
        if ($berat && $tgl) {
            $grafik = Panen::get()
                ->groupBy(fn($p) => Carbon::parse($p->$tgl)->format('Y-m'))
                ->map(fn($g) => $g->sum($berat))
                ->sortKeys();
        }

        $terbaru = Panen::orderByDesc($tgl ?? 'id')->take(5)->get()->map(fn($p) => [
            'tanggal' => $tgl ? Carbon::parse($p->$tgl)->format('d/m/Y') : '-',
            'berat' => $berat ? $p->$berat : 0,
            'pendapatan' => $uang ? $p->$uang : 0,
        ]);

        return view('admin.dashboard', compact(
            'totalPanen', 'totalPendapatan', 'jumlahPetani', 'jumlahKebun', 'grafik', 'terbaru'
        ));
    }
}