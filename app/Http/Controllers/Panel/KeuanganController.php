<?php

namespace App\Http\Controllers\Panel;

use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KeuanganController extends PanelController
{
    public function index(Request $request)
    {
        $base = Keuangan::where('petani_id', $this->petani()->id)
            ->when($request->jenis, fn ($q, $j) => $q->where('jenis', $j))
            ->when($request->dari, fn ($q, $d) => $q->whereDate('tanggal', '>=', $d))
            ->when($request->sampai, fn ($q, $d) => $q->whereDate('tanggal', '<=', $d));

        $pemasukan   = (clone $base)->where('jenis', 'pemasukan')->sum('jumlah');
        $pengeluaran = (clone $base)->where('jenis', 'pengeluaran')->sum('jumlah');

        $transaksi = $base->with('kebun')
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('petani.keuangan.index', compact('transaksi', 'pemasukan', 'pengeluaran'));
    }

    public function create()
    {
        return view('petani.keuangan.form', [
            'item'  => new Keuangan(['tanggal' => today()]),
            'kebun' => $this->kebunSaya()->get(),
        ]);
    }

    public function store(Request $request)
    {
        Keuangan::create($this->data($request) + ['petani_id' => $this->petani()->id]);

        return redirect()->route('petani.keuangan.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function edit(Keuangan $keuangan)
    {
        $this->pastikanMilik($keuangan);

        return view('petani.keuangan.form', [
            'item'  => $keuangan,
            'kebun' => $this->kebunSaya()->get(),
        ]);
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        $this->pastikanMilik($keuangan);
        $keuangan->update($this->data($request));

        return redirect()->route('petani.keuangan.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Keuangan $keuangan)
    {
        $this->pastikanMilik($keuangan);
        $keuangan->delete();

        return redirect()->route('petani.keuangan.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function data(Request $request): array
    {
        $pid = $this->petani()->id;

        return $this->validasi($request, [
            'jenis'      => ['required', Rule::in(['pemasukan', 'pengeluaran'])],
            'kategori'   => ['required', Rule::in(Keuangan::KATEGORI)],
            'kebun_id'   => ['nullable', Rule::exists('kebun', 'id')->where('petani_id', $pid)],
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|numeric|min:1|max:99999999999',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'jenis'      => 'Jenis transaksi',
            'kategori'   => 'Kategori',
            'kebun_id'   => 'Kebun',
            'tanggal'    => 'Tanggal',
            'jumlah'     => 'Jumlah',
            'keterangan' => 'Keterangan',
        ]);
    }
}