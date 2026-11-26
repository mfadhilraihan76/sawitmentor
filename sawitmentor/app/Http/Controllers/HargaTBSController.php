<?php

namespace App\Http\Controllers;

use App\Models\HargaTbs;
use Illuminate\Http\Request;

class HargaTbsController extends Controller
{
    public function index()
    {
        $harga = HargaTbs::orderByDesc('periode_mulai')->paginate(10);
        return view('harga_tbs.index', compact('harga'));
    }

    public function create()
    {
        return view('harga_tbs.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($this->tumpangTindih($data['periode_mulai'], $data['periode_selesai'])) {
            return back()->withInput()->with('error', 'Periode tumpang tindih dengan harga TBS yang sudah ada.');
        }
        HargaTbs::create($data);
        return redirect()->route('harga-tbs.index')->with('success', 'Harga TBS berhasil ditambahkan.');
    }

    public function edit(HargaTbs $harga_tb)
    {
        return view('harga_tbs.edit', ['harga' => $harga_tb]);
    }

    public function update(Request $request, HargaTbs $harga_tb)
    {
        $data = $this->validated($request);
        if ($this->tumpangTindih($data['periode_mulai'], $data['periode_selesai'], $harga_tb->id)) {
            return back()->withInput()->with('error', 'Periode tumpang tindih dengan harga TBS yang sudah ada.');
        }
        $harga_tb->update($data);
        return redirect()->route('harga-tbs.index')->with('success', 'Harga TBS berhasil diperbarui.');
    }

    public function destroy(HargaTbs $harga_tb)
    {
        if ($harga_tb->panen()->exists()) {
            return back()->with('error', 'Harga TBS tidak dapat dihapus karena sudah dipakai data panen.');
        }
        $harga_tb->delete();
        return redirect()->route('harga-tbs.index')->with('success', 'Harga TBS berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'periode_mulai'   => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'harga_per_kg'    => 'required|numeric|min:1',
        ]);
    }

    private function tumpangTindih(string $mulai, string $selesai, ?int $kecuali = null): bool
    {
        return HargaTbs::query()
            ->when($kecuali, fn ($q) => $q->where('id', '!=', $kecuali))
            ->where('periode_mulai', '<=', $selesai)
            ->where('periode_selesai', '>=', $mulai)
            ->exists();
    }
}