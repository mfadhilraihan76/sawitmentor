<?php

namespace App\Http\Controllers\Panel;

use App\Models\Kebun;
use App\Models\Keuangan;
use App\Models\Perawatan;
use Illuminate\Http\Request;

class KebunController extends PanelController
{
    public function index(Request $request)
    {
        $kebun = Kebun::where('petani_id', $this->petani()->id)
            ->withCount('panen')
            ->withSum('panen as total_berat', 'berat_kg')
            ->withSum('panen as total_pendapatan', 'total_pendapatan')
            ->when($request->q, fn ($q, $kw) => $q->where('nama_kebun', 'like', "%{$kw}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petani.kebun.index', compact('kebun'));
    }

    public function create()
    {
        return view('petani.kebun.form', ['item' => new Kebun]);
    }

    public function store(Request $request)
    {
        Kebun::create($this->data($request) + ['petani_id' => $this->petani()->id]);

        return redirect()->route('petani.kebun')->with('success', 'Kebun berhasil ditambahkan.');
    }

    public function edit(Kebun $kebun)
    {
        $this->pastikanMilik($kebun);

        return view('petani.kebun.form', ['item' => $kebun]);
    }

    public function update(Request $request, Kebun $kebun)
    {
        $this->pastikanMilik($kebun);
        $kebun->update($this->data($request));

        return redirect()->route('petani.kebun')->with('success', 'Data kebun berhasil diperbarui.');
    }

    public function destroy(Kebun $kebun)
    {
        $this->pastikanMilik($kebun);

        if ($kebun->panen()->exists()) {
            return back()->with('error', 'Kebun tidak dapat dihapus karena sudah memiliki data panen.');
        }

        // catatan keuangan dan perawatan tetap ada, hanya dilepas dari kebun ini
        Perawatan::where('kebun_id', $kebun->id)->update(['kebun_id' => null]);
        Keuangan::where('kebun_id', $kebun->id)->update(['kebun_id' => null]);
        $kebun->delete();

        return redirect()->route('petani.kebun')->with('success', 'Kebun berhasil dihapus.');
    }

    private function data(Request $request): array
    {
        return $this->validasi($request, [
            'nama_kebun' => 'required|string|max:255',
            'lokasi'     => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0.01|max:999999',
        ], [
            'nama_kebun' => 'Nama kebun',
            'lokasi'     => 'Lokasi',
            'luas_lahan' => 'Luas lahan',
        ]);
    }
}