<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index(Request $request)
    {
        $petani = Petani::query()
            ->when($request->q, fn ($q, $kw) => $q->where('nama', 'like', "%{$kw}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petani.index', compact('petani'));
    }

    public function create()
    {
        return view('petani.create');
    }

    public function store(Request $request)
    {
        Petani::create($this->validated($request));
        return redirect()->route('petani.index')->with('success', 'Data petani berhasil ditambahkan.');
    }

    public function edit(Petani $petani)
    {
        return view('petani.edit', compact('petani'));
    }

    public function update(Request $request, Petani $petani)
    {
        $petani->update($this->validated($request));
        return redirect()->route('petani.index')->with('success', 'Data petani berhasil diperbarui.');
    }

    public function destroy(Petani $petani)
    {
        if ($petani->kebun()->exists()) {
            return back()->with('error', 'Petani tidak dapat dihapus karena masih memiliki data kebun.');
        }
        $petani->delete();
        return redirect()->route('petani.index')->with('success', 'Data petani berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama'   => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp'  => 'required|string|max:20',
        ]);
    }
}