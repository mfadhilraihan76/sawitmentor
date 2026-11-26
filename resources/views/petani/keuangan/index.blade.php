@extends('layouts.sawit_top')
@use('App\Support\Fmt')
@section('title', 'Keuangan')

@section('content')
@include('petani._panel-style')
<div class="page-head">
    <div>
        <h1>Pemasukan &amp; pengeluaran</h1>
        <p>Catat biaya kebun dan pemasukan di luar hasil panen. Pendapatan panen sudah dihitung otomatis di menu Hasil panen.</p>
    </div>
    <a href="{{ route('petani.keuangan.create') }}" class="btn btn-sawit"><i class="bi bi-plus-lg"></i> Tambah transaksi</a>
</div>

<form method="GET" action="{{ route('petani.keuangan.index') }}" class="filter no-print">
    <div>
        <label for="jenis">Jenis</label>
        <select name="jenis" id="jenis" class="form-select">
            <option value="">Semua</option>
            <option value="pemasukan" @selected(request('jenis') === 'pemasukan')>Pemasukan</option>
            <option value="pengeluaran" @selected(request('jenis') === 'pengeluaran')>Pengeluaran</option>
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
    @if (request()->hasAny(['jenis', 'dari', 'sampai']))
        <a href="{{ route('petani.keuangan.index') }}" class="btn btn-outline-secondary">Reset</a>
    @endif
</form>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="kartu stat"><div class="lbl">Pemasukan</div><div class="val text-success">{{ Fmt::rp($pemasukan) }}</div></div>
    </div>
    <div class="col-md-4">
        <div class="kartu stat" style="--a:#e0485a"><div class="lbl">Pengeluaran</div><div class="val text-danger">{{ Fmt::rp($pengeluaran) }}</div></div>
    </div>
    <div class="col-md-4">
        <div class="kartu stat" style="--a:#2f6db5"><div class="lbl">Selisih</div><div class="val">{{ Fmt::rp($pemasukan - $pengeluaran) }}</div></div>
    </div>
</div>

<div class="kartu p-0">
    @if ($transaksi->isEmpty())
        <div class="empty">
            <i class="bi bi-wallet2"></i>
            <div class="fw-semibold text-dark">Belum ada transaksi</div>
            <p class="mb-3">Catat pembelian pupuk, upah, atau pemasukan lain di sini.</p>
            <a href="{{ route('petani.keuangan.create') }}" class="btn btn-sawit">Tambah transaksi</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Kebun</th>
                        <th>Keterangan</th>
                        <th class="num">Jumlah</th>
                        <th class="num no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $t)
                        <tr>
                            <td>{{ Fmt::tgl($t->tanggal) }}</td>
                            <td>
                                @if ($t->jenis === 'pemasukan')
                                    <span class="pill pill-green">Pemasukan</span>
                                @else
                                    <span class="pill pill-red">Pengeluaran</span>
                                @endif
                            </td>
                            <td>{{ $t->kategori }}</td>
                            <td>{{ $t->kebun->nama_kebun ?? '-' }}</td>
                            <td class="sub">{{ $t->keterangan ?: '-' }}</td>
                            <td class="num fw-semibold {{ $t->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                {{ $t->jenis === 'pemasukan' ? '+' : '-' }}{{ Fmt::rp($t->jumlah) }}
                            </td>
                            <td class="num no-print">
                                <div class="aksi">
                                    <a href="{{ route('petani.keuangan.edit', $t) }}" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('petani.keuangan.destroy', $t) }}" onsubmit="return confirm('Hapus transaksi ini?')">
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

<div class="mt-3 no-print">{{ $transaksi->links('pagination::bootstrap-5') }}</div>
@endsection