<?php

namespace App\Http\Controllers;

use App\Models\Kebun;
use App\Models\Petani;
use Illuminate\Http\Request;

class KebunController extends Controller
{
    public function index(Request $request)
    {
        $kebun = Kebun::with('petani')
            ->when($request->q, fn ($q, $kw) => $q->where('nama_kebun', 'like', "%{$kw}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kebun.index', compact('kebun'));
    }

    public function create()
    {
        $petani = Petani::orderBy('nama')->get();
        return view('kebun.create', compact('petani'));
    }

    public function store(Request $request)
    {
        Kebun::create($this->validated($request));
        return redirect()->route('kebun.index')->with('success', 'Data kebun berhasil ditambahkan.');
    }

    public function edit(Kebun $kebun)
    {
        $petani = Petani::orderBy('nama')->get();
        return view('kebun.edit', compact('kebun', 'petani'));
    }

    public function update(Request $request, Kebun $kebun)
    {
        $kebun->update($this->validated($request));
        return redirect()->route('kebun.index')->with('success', 'Data kebun berhasil diperbarui.');
    }

    public function destroy(Kebun $kebun)
    {
        if ($kebun->panen()->exists()) {
            return back()->with('error', 'Kebun tidak dapat dihapus karena sudah memiliki data panen.');
        }
        $kebun->delete();
        return redirect()->route('kebun.index')->with('success', 'Data kebun berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'petani_id'  => 'required|exists:petani,id',
            'nama_kebun' => 'required|string|max:255',
            'lokasi'     => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0.01',
        ]);
    }
}