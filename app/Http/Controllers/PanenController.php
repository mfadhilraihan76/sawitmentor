<?php

namespace App\Http\Controllers;

use App\Models\HargaTbs;
use App\Models\Kebun;
use App\Models\Panen;
use Illuminate\Http\Request;

class PanenController extends Controller
{
    public function index(Request $request)
    {
        $panen = Panen::with(['kebun.petani', 'hargaTbs'])
            ->when($request->kebun_id, fn ($q, $id) => $q->where('kebun_id', $id))
            ->when($request->dari, fn ($q, $d) => $q->whereDate('tanggal_panen', '>=', $d))
            ->when($request->sampai, fn ($q, $d) => $q->whereDate('tanggal_panen', '<=', $d))
            ->latest('tanggal_panen')
            ->paginate(10)
            ->withQueryString();

        $kebun = Kebun::orderBy('nama_kebun')->get();
        return view('panen.index', compact('panen', 'kebun'));
    }

    public function create()
    {
        $kebun = Kebun::with('petani')->orderBy('nama_kebun')->get();
        return view('panen.create', compact('kebun'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $harga = $this->cariHarga($data['tanggal_panen']);

        if (! $harga) {
            return back()->withInput()->with('error', 'Belum ada harga TBS yang berlaku pada tanggal panen tersebut.');
        }

        $data['harga_tbs_id']     = $harga->id;
        $data['total_pendapatan'] = $data['berat_kg'] * $harga->harga_per_kg;

        Panen::create($data);
        return redirect()->route('panen.index')->with('success', 'Hasil panen berhasil dicatat.');
    }

    public function edit(Panen $panen)
    {
        $kebun = Kebun::with('petani')->orderBy('nama_kebun')->get();
        return view('panen.edit', compact('panen', 'kebun'));
    }

    public function update(Request $request, Panen $panen)
    {
        $data = $this->validated($request);
        $harga = $this->cariHarga($data['tanggal_panen']);

        if (! $harga) {
            return back()->withInput()->with('error', 'Belum ada harga TBS yang berlaku pada tanggal panen tersebut.');
        }

        $data['harga_tbs_id']     = $harga->id;
        $data['total_pendapatan'] = $data['berat_kg'] * $harga->harga_per_kg;

        $panen->update($data);
        return redirect()->route('panen.index')->with('success', 'Hasil panen berhasil diperbarui.');
    }

    public function destroy(Panen $panen)
    {
        $panen->delete();
        return redirect()->route('panen.index')->with('success', 'Hasil panen berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'kebun_id'      => 'required|exists:kebun,id',
            'tanggal_panen' => 'required|date',
            'berat_kg'      => 'required|numeric|min:0.01',
        ]);
    }

    private function cariHarga(string $tanggal): ?HargaTbs
    {
        return HargaTbs::whereDate('periode_mulai', '<=', $tanggal)
            ->whereDate('periode_selesai', '>=', $tanggal)
            ->first();
    }
}