<?php

namespace App\Http\Controllers\Panel;

use App\Models\HargaTbs;
use Illuminate\Http\Request;

class HargaTbsController extends PanelController
{
    public function index()
    {
        $harga = HargaTbs::orderByDesc('periode_mulai')->paginate(10);

        $hariIni = HargaTbs::whereDate('periode_mulai', '<=', today())
            ->whereDate('periode_selesai', '>=', today())
            ->orderByDesc('periode_mulai')
            ->first();

        return view('petani.harga-tbs.index', compact('harga', 'hariIni'));
    }

    public function create()
    {
        return view('petani.harga-tbs.form', ['item' => new HargaTbs]);
    }

    public function store(Request $request)
    {
        $data = $this->data($request);

        if ($this->tumpangTindih($data['periode_mulai'], $data['periode_selesai'])) {
            return back()->withInput()->with('error', 'Periode tumpang tindih dengan harga TBS yang sudah ada.');
        }

        HargaTbs::create($data);

        return redirect()->route('petani.harga-tbs.index')->with('success', 'Harga TBS berhasil ditambahkan.');
    }

    public function edit(HargaTbs $harga)
    {
        return view('petani.harga-tbs.form', ['item' => $harga]);
    }

    public function update(Request $request, HargaTbs $harga)
    {
        $data = $this->data($request);

        if ($this->tumpangTindih($data['periode_mulai'], $data['periode_selesai'], $harga->id)) {
            return back()->withInput()->with('error', 'Periode tumpang tindih dengan harga TBS yang sudah ada.');
        }

        $harga->update($data);

        return redirect()->route('petani.harga-tbs.index')->with('success', 'Harga TBS berhasil diperbarui.');
    }

    public function destroy(HargaTbs $harga)
    {
        if ($harga->panen()->exists()) {
            return back()->with('error', 'Harga TBS tidak dapat dihapus karena sudah dipakai data panen.');
        }

        $harga->delete();

        return redirect()->route('petani.harga-tbs.index')->with('success', 'Harga TBS berhasil dihapus.');
    }

    private function data(Request $request): array
    {
        return $this->validasi($request, [
            'periode_mulai'   => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'harga_per_kg'    => 'required|numeric|min:1|max:9999999999',
        ], [
            'periode_mulai'   => 'Periode mulai',
            'periode_selesai' => 'Periode selesai',
            'harga_per_kg'    => 'Harga per kg',
        ]);
    }

    private function tumpangTindih(string $mulai, string $selesai, ?int $kecuali = null): bool
    {
        return HargaTbs::query()
            ->when($kecuali, fn ($q) => $q->where('id', '!=', $kecuali))
            ->whereDate('periode_mulai', '<=', $selesai)
            ->whereDate('periode_selesai', '>=', $mulai)
            ->exists();
    }
}