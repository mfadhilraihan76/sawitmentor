<?php

namespace App\Http\Controllers\Panel;

use App\Models\Keuangan;
use App\Models\Laporan;
use App\Models\Panen;
use App\Models\Perawatan;
use Illuminate\Http\Request;

class LaporanController extends PanelController
{
    public function index()
    {
        $laporan = Laporan::where('petani_id', $this->petani()->id)
            ->latest('dari')
            ->latest('id')
            ->paginate(10);

        return view('petani.laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('petani.laporan.form', [
            'item' => new Laporan(['dari' => now()->startOfMonth(), 'sampai' => today()]),
        ]);
    }

    public function store(Request $request)
    {
        $laporan = Laporan::create($this->data($request) + ['petani_id' => $this->petani()->id]);

        return redirect()->route('petani.laporan.show', $laporan)->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(Laporan $laporan)
    {
        $this->pastikanMilik($laporan);

        $pid    = $this->petani()->id;
        $dari   = $laporan->dari->toDateString();
        $sampai = $laporan->sampai->toDateString();

        $panen = Panen::with('kebun')
            ->where('petani_id', $pid)
            ->whereBetween('tanggal_panen', [$dari, $sampai])
            ->orderBy('tanggal_panen')
            ->get();

        $keuangan = Keuangan::where('petani_id', $pid)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->get();

        $totalBerat      = $panen->sum('berat_kg');
        $pendapatanPanen = $panen->sum('total_pendapatan');
        $pemasukanLain   = $keuangan->where('jenis', 'pemasukan')->sum('jumlah');
        $pengeluaran     = $keuangan->where('jenis', 'pengeluaran')->sum('jumlah');
        $laba            = $pendapatanPanen + $pemasukanLain - $pengeluaran;

        $perKebun = $panen
            ->groupBy(fn ($p) => $p->kebun->nama_kebun ?? '-')
            ->map(fn ($g) => [
                'jumlah'     => $g->count(),
                'berat'      => $g->sum('berat_kg'),
                'pendapatan' => $g->sum('total_pendapatan'),
            ]);

        $perKategori = $keuangan->where('jenis', 'pengeluaran')
            ->groupBy('kategori')
            ->map(fn ($g) => $g->sum('jumlah'))
            ->sortDesc();

        $perawatanSelesai = Perawatan::where('petani_id', $pid)
            ->where('status', 'selesai')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->count();

        return view('petani.laporan.show', [
            'laporan'          => $laporan,
            'petani'           => $this->petani(),
            'panen'            => $panen,
            'totalBerat'       => $totalBerat,
            'pendapatanPanen'  => $pendapatanPanen,
            'pemasukanLain'    => $pemasukanLain,
            'pengeluaran'      => $pengeluaran,
            'laba'             => $laba,
            'perKebun'         => $perKebun,
            'perKategori'      => $perKategori,
            'perawatanSelesai' => $perawatanSelesai,
        ]);
    }

    public function edit(Laporan $laporan)
    {
        $this->pastikanMilik($laporan);

        return view('petani.laporan.form', ['item' => $laporan]);
    }

    public function update(Request $request, Laporan $laporan)
    {
        $this->pastikanMilik($laporan);
        $laporan->update($this->data($request));

        return redirect()->route('petani.laporan.show', $laporan)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan)
    {
        $this->pastikanMilik($laporan);
        $laporan->delete();

        return redirect()->route('petani.laporan')->with('success', 'Laporan berhasil dihapus.');
    }

    private function data(Request $request): array
    {
        return $this->validasi($request, [
            'judul'   => 'required|string|max:150',
            'dari'    => 'required|date',
            'sampai'  => 'required|date|after_or_equal:dari',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'judul'   => 'Judul laporan',
            'dari'    => 'Dari tanggal',
            'sampai'  => 'Sampai tanggal',
            'catatan' => 'Catatan',
        ]);
    }
}