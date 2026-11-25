<?php

namespace App\Http\Controllers;

use App\Models\HargaTbs;
use App\Models\Kebun;
use App\Models\Panen;
use App\Models\Petani;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PetaniDashboardController extends Controller
{
    private function kol(string $model, array $opsi)
    {
        $t = (new $model)->getTable();
        return collect($opsi)->first(fn($k) => Schema::hasColumn($t, $k));
    }

    private function petaniLogin()
    {
        $user = auth()->user();
        $tp = (new Petani)->getTable();
        $petani = null;
        if (Schema::hasColumn($tp, 'user_id')) {
            $petani = Petani::where('user_id', $user->id)->first();
        }
        if (!$petani && Schema::hasColumn($tp, 'email')) {
            $petani = Petani::where('email', $user->email)->first();
        }
        if (!$petani && Schema::hasColumn($tp, 'nama')) {
            $petani = Petani::where('nama', $user->name)->first();
        }
        return $petani;
    }

    // Semua panen milik petani yang login, sudah dipetakan jadi array rapi
    private function dataPanen($petani)
    {
        $berat = $this->kol(Panen::class, ['berat_kg', 'berat', 'total_berat', 'jumlah_kg']);
        $uang = $this->kol(Panen::class, ['total_pendapatan', 'pendapatan', 'total_harga', 'total']);
        $harga = $this->kol(Panen::class, ['harga_per_kg', 'harga_tbs', 'harga']);
        $tgl = $this->kol(Panen::class, ['tanggal_panen', 'tanggal', 'created_at']);
        $tp = (new Panen)->getTable();

        $q = Panen::query();
        if ($petani && Schema::hasColumn($tp, 'petani_id')) {
            $q->where('petani_id', $petani->id);
        } else {
            $q->whereRaw('1 = 0');
        }

        return $q->get()->map(function ($p) use ($berat, $uang, $harga, $tgl) {
            $b = $berat ? (float) $p->$berat : 0;
            $h = $harga ? (float) $p->$harga : 0;
            $u = $uang ? (float) $p->$uang : $b * $h;
            return [
                'tanggal' => $tgl && $p->$tgl ? Carbon::parse($p->$tgl) : null,
                'kebun_id' => $p->kebun_id ?? null,
                'berat' => $b,
                'harga' => $h,
                'pendapatan' => $u,
            ];
        })->sortByDesc(fn($r) => $r['tanggal'] ? $r['tanggal']->timestamp : 0)->values();
    }

    private function daftarKebun($petani)
    {
        $kt = (new Kebun)->getTable();
        if (!$petani || !Schema::hasColumn($kt, 'petani_id')) {
            return collect();
        }
        return Kebun::where('petani_id', $petani->id)->get();
    }

    private function namaKebun($kebun): string
    {
        $kt = (new Kebun)->getTable();
        foreach (['nama_kebun', 'nama', 'lokasi'] as $k) {
            if (Schema::hasColumn($kt, $k) && $kebun->$k) {
                return $kebun->$k;
            }
        }
        return 'Kebun #' . $kebun->id;
    }

    private function hargaSekarang()
    {
        return HargaTbs::where('periode_mulai', '<=', today())
            ->where('periode_selesai', '>=', today())
            ->first();
    }

    // 1. Dashboard saya
    public function index()
    {
        $user = auth()->user();
        $petani = $this->petaniLogin();
        $panen = $this->dataPanen($petani);
        $kebun = $this->daftarKebun($petani);

        $totalPanen = $panen->sum('berat');
        $totalPendapatan = $panen->sum('pendapatan');
        $jumlahKebun = $kebun->count();
        $bulanIni = $panen->filter(fn($r) => $r['tanggal'] && $r['tanggal']->isSameMonth(now()))->sum('berat');

        $grafik = $panen->filter(fn($r) => $r['tanggal'])
            ->groupBy(fn($r) => $r['tanggal']->format('Y-m'))
            ->map(fn($g) => $g->sum('berat'))
            ->sortKeys();

        $terbaru = $panen->take(5);
        $hargaSekarang = $this->hargaSekarang();

        return view('petani.dashboard', compact(
            'user', 'petani', 'totalPanen', 'totalPendapatan', 'jumlahKebun',
            'bulanIni', 'grafik', 'terbaru', 'hargaSekarang'
        ));
    }

    // 2. Kebun dan riwayat
    public function kebun()
    {
        $petani = $this->petaniLogin();
        $panen = $this->dataPanen($petani);
        $kebunList = $this->daftarKebun($petani);

        $namaMap = $kebunList->mapWithKeys(fn($k) => [$k->id => $this->namaKebun($k)]);

        $ringkasan = $kebunList->map(function ($k) use ($panen, $namaMap) {
            $p = $panen->where('kebun_id', $k->id);
            return [
                'nama' => $namaMap[$k->id],
                'luas' => $k->luas ?? $k->luas_ha ?? null,
                'berat' => $p->sum('berat'),
                'pendapatan' => $p->sum('pendapatan'),
            ];
        });

        $riwayat = $panen->map(function ($r) use ($namaMap) {
            $r['kebun'] = $namaMap[$r['kebun_id']] ?? '-';
            return $r;
        });

        return view('petani.kebun', compact('petani', 'ringkasan', 'riwayat'));
    }

    // 3. Laporan saya (filter periode + cetak)
    public function laporan(Request $request)
    {
        $user = auth()->user();
        $petani = $this->petaniLogin();
        $namaMap = $this->daftarKebun($petani)->mapWithKeys(fn($k) => [$k->id => $this->namaKebun($k)]);

        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $rows = $this->dataPanen($petani)->filter(function ($r) use ($dari, $sampai) {
            if (!$r['tanggal']) {
                return !$dari && !$sampai;
            }
            if ($dari && $r['tanggal']->lt(Carbon::parse($dari)->startOfDay())) {
                return false;
            }
            if ($sampai && $r['tanggal']->gt(Carbon::parse($sampai)->endOfDay())) {
                return false;
            }
            return true;
        })->map(function ($r) use ($namaMap) {
            $r['kebun'] = $namaMap[$r['kebun_id']] ?? '-';
            return $r;
        })->values();

        $totalBerat = $rows->sum('berat');
        $totalPendapatan = $rows->sum('pendapatan');

        return view('petani.laporan', compact('user', 'petani', 'rows', 'dari', 'sampai', 'totalBerat', 'totalPendapatan'));
    }
}