@extends('layouts.petani-panel')
@use('App\Support\Fmt')
@section('title', $laporan->judul)

@section('content')
<div class="page-head no-print">
    <div>
        <h1>{{ $laporan->judul }}</h1>
        <p>Periode {{ Fmt::tgl($laporan->dari) }} s/d {{ Fmt::tgl($laporan->sampai) }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('petani.laporan') }}" class="btn btn-outline-secondary">Kembali</a>
        <a href="{{ route('petani.laporan.edit', $laporan) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</a>
        <button type="button" onclick="window.print()" class="btn btn-dark"><i class="bi bi-printer"></i> Cetak</button>
    </div>
</div>

{{-- Kop saat dicetak --}}
<div class="d-none d-print-block text-center mb-3">
    <img src="{{ asset('images/logo.png') }}" alt="SawitMentor" style="height:60px">
    <h4 class="mt-2 mb-0">{{ $laporan->judul }}</h4>
    <div>Petani: {{ $petani->nama }} &bull; Periode {{ Fmt::tgl($laporan->dari) }} s/d {{ Fmt::tgl($laporan->sampai) }}</div>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg">
        <div class="kartu stat"><div class="lbl">Total panen</div><div class="val">{{ Fmt::kg($totalBerat) }} kg</div></div>
    </div>
    <div class="col-6 col-lg">
        <div class="kartu stat" style="--a:#f2b632"><div class="lbl">Pendapatan panen</div><div class="val">{{ Fmt::rp($pendapatanPanen) }}</div></div>
    </div>
    <div class="col-6 col-lg">
        <div class="kartu stat" style="--a:#2f6db5"><div class="lbl">Pemasukan lain</div><div class="val">{{ Fmt::rp($pemasukanLain) }}</div></div>
    </div>
    <div class="col-6 col-lg">
        <div class="kartu stat" style="--a:#e0485a"><div class="lbl">Pengeluaran</div><div class="val">{{ Fmt::rp($pengeluaran) }}</div></div>
    </div>
    <div class="col-12 col-lg">
        <div class="kartu stat" style="--a:#7c5cd6">
            <div class="lbl">Keuntungan bersih</div>
            <div class="val {{ $laba < 0 ? 'text-danger' : 'text-success' }}">{{ Fmt::rp($laba) }}</div>
        </div>
    </div>
</div>

@if ($laporan->catatan)
    <div class="kartu mb-3"><div class="sub mb-1">Catatan</div>{{ $laporan->catatan }}</div>
@endif

<div class="row g-3 mb-3">
    <div class="col-lg-7">
        <div class="kartu p-0 h-100">
            <div class="px-3 pt-3 fw-semibold">Ringkasan per kebun</div>
            <div class="table-responsive">
                <table class="tbl">
                    <thead><tr><th>Kebun</th><th class="num">Jumlah panen</th><th class="num">Berat (kg)</th><th class="num">Pendapatan</th></tr></thead>
                    <tbody>
                        @forelse ($perKebun as $nama => $r)
                            <tr>
                                <td>{{ $nama }}</td>
                                <td class="num">{{ $r['jumlah'] }}</td>
                                <td class="num">{{ Fmt::kg($r['berat']) }}</td>
                                <td class="num">{{ Fmt::rp($r['pendapatan']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Tidak ada panen pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="kartu p-0 h-100" style="--a:#e0485a">
            <div class="px-3 pt-3 fw-semibold">Pengeluaran per kategori</div>
            <div class="table-responsive">
                <table class="tbl">
                    <thead><tr><th>Kategori</th><th class="num">Jumlah</th></tr></thead>
                    <tbody>
                        @forelse ($perKategori as $kategori => $jumlah)
                            <tr><td>{{ $kategori }}</td><td class="num">{{ Fmt::rp($jumlah) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted">Tidak ada pengeluaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 pb-3 sub">Perawatan selesai pada periode ini: <strong>{{ $perawatanSelesai }}</strong></div>
        </div>
    </div>
</div>

<div class="kartu p-0">
    <div class="px-3 pt-3 fw-semibold">Rincian panen</div>
    <div class="table-responsive">
        <table class="tbl">
            <thead>
                <tr><th>Tanggal</th><th>Kebun</th><th class="num">Berat (kg)</th><th class="num">Harga/kg</th><th class="num">Pendapatan</th></tr>
            </thead>
            <tbody>
                @forelse ($panen as $p)
                    <tr>
                        <td>{{ Fmt::tgl($p->tanggal_panen) }}</td>
                        <td>{{ $p->kebun->nama_kebun ?? '-' }}</td>
                        <td class="num">{{ Fmt::kg($p->berat_kg) }}</td>
                        <td class="num">{{ Fmt::rp($p->harga_per_kg) }}</td>
                        <td class="num">{{ Fmt::rp($p->total_pendapatan) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Tidak ada data panen pada periode ini.</td></tr>
                @endforelse
            </tbody>
            @if ($panen->count())
                <tfoot>
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="num">{{ Fmt::kg($totalBerat) }}</td>
                        <td></td>
                        <td class="num">{{ Fmt::rp($pendapatanPanen) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

<div class="sub mt-3 text-end">Dicetak {{ now()->translatedFormat('d F Y, H:i') }}</div>
@endsection