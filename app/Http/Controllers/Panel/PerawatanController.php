<?php

namespace App\Http\Controllers\Panel;

use App\Models\Perawatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerawatanController extends PanelController
{
    public function index(Request $request)
    {
        $perawatan = Perawatan::where('petani_id', $this->petani()->id)
            ->with('kebun')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('petani.perawatan.index', compact('perawatan'));
    }

    public function create()
    {
        return view('petani.perawatan.form', [
            'item'  => new Perawatan(['tanggal' => today(), 'status' => 'terjadwal']),
            'kebun' => $this->kebunSaya()->get(),
        ]);
    }

    public function store(Request $request)
    {
        Perawatan::create($this->data($request) + ['petani_id' => $this->petani()->id]);

        return redirect()->route('petani.perawatan.index')->with('success', 'Jadwal perawatan berhasil ditambahkan.');
    }

    public function edit(Perawatan $perawatan)
    {
        $this->pastikanMilik($perawatan);

        return view('petani.perawatan.form', [
            'item'  => $perawatan,
            'kebun' => $this->kebunSaya()->get(),
        ]);
    }

    public function update(Request $request, Perawatan $perawatan)
    {
        $this->pastikanMilik($perawatan);
        $perawatan->update($this->data($request));

        return redirect()->route('petani.perawatan.index')->with('success', 'Jadwal perawatan berhasil diperbarui.');
    }

    public function selesai(Perawatan $perawatan)
    {
        $this->pastikanMilik($perawatan);
        $perawatan->update(['status' => 'selesai']);

        return back()->with('success', 'Perawatan ditandai selesai.');
    }

    public function destroy(Perawatan $perawatan)
    {
        $this->pastikanMilik($perawatan);
        $perawatan->delete();

        return redirect()->route('petani.perawatan.index')->with('success', 'Jadwal perawatan berhasil dihapus.');
    }

    private function data(Request $request): array
    {
        $pid = $this->petani()->id;

        return $this->validasi($request, [
            'jenis'    => ['required', Rule::in(Perawatan::JENIS)],
            'kebun_id' => ['nullable', Rule::exists('kebun', 'id')->where('petani_id', $pid)],
            'tanggal'  => 'required|date',
            'status'   => ['required', Rule::in(['terjadwal', 'selesai'])],
            'catatan'  => 'nullable|string|max:500',
        ], [
            'jenis'    => 'Jenis perawatan',
            'kebun_id' => 'Kebun',
            'tanggal'  => 'Tanggal',
            'status'   => 'Status',
            'catatan'  => 'Catatan',
        ]);
    }
}