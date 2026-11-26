@extends('layouts.sawit_top')
@use('App\Support\Fmt')
@section('title', 'Data kebun')

@section('content')
@include('petani._panel-style')
<div class="page-head">
    <div>
        <h1>Data kebun</h1>
        <p>Kebun milik Anda beserta total hasil panennya.</p>
    </div>
    <a href="{{ route('petani.kebun.create') }}" class="btn btn-sawit"><i class="bi bi-plus-lg"></i> Tambah kebun</a>
</div>

<form method="GET" action="{{ route('petani.kebun') }}" class="filter no-print">
    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama kebun">
    <button class="btn btn-dark">Cari</button>
    @if (request('q'))
        <a href="{{ route('petani.kebun') }}" class="btn btn-outline-secondary">Reset</a>
    @endif
</form>

<div class="kartu p-0">
    @if ($kebun->isEmpty())
        <div class="empty">
            <i class="bi bi-flower1"></i>
            <div class="fw-semibold text-dark">Belum ada kebun</div>
            <p class="mb-3">Tambahkan kebun pertama Anda untuk mulai mencatat panen.</p>
            <a href="{{ route('petani.kebun.create') }}" class="btn btn-sawit">Tambah kebun</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Kebun</th>
                        <th class="num">Luas (ha)</th>
                        <th class="num">Jumlah panen</th>
                        <th class="num">Total berat (kg)</th>
                        <th class="num">Total pendapatan</th>
                        <th class="num no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kebun as $k)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $k->nama_kebun }}</div>
                                <div class="sub"><i class="bi bi-geo-alt"></i> {{ $k->lokasi }}</div>
                            </td>
                            <td class="num">{{ Fmt::kg($k->luas_lahan) }}</td>
                            <td class="num">{{ $k->panen_count }}</td>
                            <td class="num">{{ Fmt::kg($k->total_berat ?? 0) }}</td>
                            <td class="num">{{ Fmt::rp($k->total_pendapatan ?? 0) }}</td>
                            <td class="num no-print">
                                <div class="aksi">
                                    <a href="{{ route('petani.kebun.edit', $k) }}" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('petani.kebun.destroy', $k) }}" onsubmit="return confirm('Hapus kebun {{ addslashes($k->nama_kebun) }}?')">
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

<div class="mt-3 no-print">{{ $kebun->links('pagination::bootstrap-5') }}</div>
@endsection