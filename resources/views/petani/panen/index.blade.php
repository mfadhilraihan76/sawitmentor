@extends('layouts.sawit_top')
@use('App\Support\Fmt')
@section('title', 'Hasil panen')

@section('content')
@include('petani._panel-style')
<div class="page-head">
    <div>
        <h1>Hasil panen</h1>
        <p>Catatan panen tiap kebun. Pendapatan dihitung otomatis dari harga TBS yang berlaku.</p>
    </div>
    <a href="{{ route('petani.panen.create') }}" class="btn btn-sawit"><i class="bi bi-plus-lg"></i> Catat panen</a>
</div>

<form method="GET" action="{{ route('petani.panen.index') }}" class="filter no-print">
    <div>
        <label for="kebun_id">Kebun</label>
        <select name="kebun_id" id="kebun_id" class="form-select">
            <option value="">Semua kebun</option>
            @foreach ($kebun as $k)
                <option value="{{ $k->id }}" @selected(request('kebun_id') == $k->id)>{{ $k->nama_kebun }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="dari">Dari tanggal</label>
        <input type="date" name="dari" id="dari" value="{{ request('dari') }}" class="form-control">
    </div>
    <div>
        <label for="sampai">Sampai tanggal</label>
        <input type="date" name="sampai" id="sampai" value="{{ request('sampai') }}" class="form-control">
    </div>
    <button class="btn btn-dark">Terapkan</button>
    @if (request()->hasAny(['kebun_id', 'dari', 'sampai']))
        <a href="{{ route('petani.panen.index') }}" class="btn btn-outline-secondary">Reset</a>
    @endif
</form>

<div class="row g-3 mb-3">
    <div class="col-6">
        <div class="kartu stat"><div class="lbl">Total berat</div><div class="val">{{ Fmt::kg($totalBerat) }} kg</div></div>
    </div>
    <div class="col-6">
        <div class="kartu stat" style="--a:#f2b632"><div class="lbl">Total pendapatan</div><div class="val">{{ Fmt::rp($totalPendapatan) }}</div></div>
    </div>
</div>

<div class="kartu p-0">
    @if ($panen->isEmpty())
        <div class="empty">
            <i class="bi bi-basket"></i>
            <div class="fw-semibold text-dark">Belum ada data panen</div>
            <p class="mb-3">Catat hasil panen pertama Anda.</p>
            <a href="{{ route('petani.panen.create') }}" class="btn btn-sawit">Catat panen</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kebun</th>
                        <th class="num">Berat (kg)</th>
                        <th class="num">Harga/kg</th>
                        <th class="num">Pendapatan</th>
                        <th class="num no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($panen as $p)
                        <tr>
                            <td>{{ Fmt::tgl($p->tanggal_panen) }}</td>
                            <td>{{ $p->kebun->nama_kebun ?? '-' }}</td>
                            <td class="num">{{ Fmt::kg($p->berat_kg) }}</td>
                            <td class="num">{{ Fmt::rp($p->harga_per_kg) }}</td>
                            <td class="num fw-semibold">{{ Fmt::rp($p->total_pendapatan) }}</td>
                            <td class="num no-print">
                                <div class="aksi">
                                    <a href="{{ route('petani.panen.edit', $p) }}" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('petani.panen.destroy', $p) }}" onsubmit="return confirm('Hapus data panen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-icon hapus" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="mt-3 no-print">{{ $panen->links('pagination::bootstrap-5') }}</div>
@endsection