<?php

namespace App\Http\Controllers\Panel;

use App\Models\HargaTbs;
use App\Models\Panen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PanenController extends PanelController
{
    public function index(Request $request)
    {
        $base = Panen::where('petani_id', $this->petani()->id)
            ->when($request->kebun_id, fn ($q, $id) => $q->where('kebun_id', $id))
            ->when($request->dari, fn ($q, $d) => $q->whereDate('tanggal_panen', '>=', $d))
            ->when($request->sampai, fn ($q, $d) => $q->whereDate('tanggal_panen', '<=', $d));

        $totalBerat      = (clone $base)->sum('berat_kg');
        $totalPendapatan = (clone $base)->sum('total_pendapatan');

        $panen = $base->with('kebun')
            ->latest('tanggal_panen')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kebun = $this->kebunSaya()->get();

        return view('petani.panen.index', compact('panen', 'kebun', 'totalBerat', 'totalPendapatan'));
    }

    public function create()
    {
        $kebun = $this->kebunSaya()->get();

        if ($kebun->isEmpty()) {
            return redirect()->route('petani.kebun.create')
                ->with('error', 'Tambahkan kebun dulu sebelum mencatat hasil panen.');
        }

        return view('petani.panen.form', [
            'item'      => new Panen(['tanggal_panen' => today()]),
            'kebun'     => $kebun,
            'hargaList' => $this->hargaList(),
        ]);
    }

    public function store(Request $request)
    {
        return $this->simpan($request);
    }

    public function edit(Panen $panen)
    {
        $this->pastikanMilik($panen);

        return view('petani.panen.form', [
            'item'      => $panen,
            'kebun'     => $this->kebunSaya()->get(),
            'hargaList' => $this->hargaList(),
        ]);
    }

    public function update(Request $request, Panen $panen)
    {
        $this->pastikanMilik($panen);

        return $this->simpan($request, $panen);
    }

    public function destroy(Panen $panen)
    {
        $this->pastikanMilik($panen);
        $panen->delete();

        return redirect()->route('petani.panen.index')->with('success', 'Hasil panen berhasil dihapus.');
    }

    private function simpan(Request $request, ?Panen $panen = null)
    {
        $pid = $this->petani()->id;

        $data = $this->validasi($request, [
            'kebun_id'      => ['required', Rule::exists('kebun', 'id')->where('petani_id', $pid)],
            'tanggal_panen' => 'required|date',
            'berat_kg'      => 'required|numeric|min:0.01|max:99999999',
        ], [
            'kebun_id'      => 'Kebun',
            'tanggal_panen' => 'Tanggal panen',
            'berat_kg'      => 'Berat panen',
        ]);

        $harga = HargaTbs::whereDate('periode_mulai', '<=', $data['tanggal_panen'])
            ->whereDate('periode_selesai', '>=', $data['tanggal_panen'])
            ->orderByDesc('periode_mulai')
            ->first();

        if (! $harga) {
            return back()->withInput()->with(
                'error',
                'Belum ada harga TBS yang berlaku pada tanggal tersebut. Tambahkan dulu di menu Keuangan > Harga TBS.'
            );
        }

        $isi = [
            'petani_id'        => $pid,
            'kebun_id'         => $data['kebun_id'],
            'tanggal_panen'    => $data['tanggal_panen'],
            'berat_kg'         => $data['berat_kg'],
            'harga_tbs_id'     => $harga->id,
            'harga_per_kg'     => $harga->harga_per_kg,
            'total_pendapatan' => round($data['berat_kg'] * $harga->harga_per_kg, 2),
        ];

        if ($panen) {
            $panen->update($isi);
            $pesan = 'Hasil panen berhasil diperbarui.';
        } else {
            Panen::create($isi);
            $pesan = 'Hasil panen berhasil dicatat.';
        }

        return redirect()->route('petani.panen.index')->with('success', $pesan);
    }

    /** Daftar harga TBS untuk pratinjau pendapatan di form. */
    private function hargaList(): array
    {
        return HargaTbs::orderByDesc('periode_mulai')->get()->map(fn ($h) => [
            'mulai'   => $h->periode_mulai->format('Y-m-d'),
            'selesai' => $h->periode_selesai->format('Y-m-d'),
            'harga'   => (float) $h->harga_per_kg,
        ])->all();
    }
}