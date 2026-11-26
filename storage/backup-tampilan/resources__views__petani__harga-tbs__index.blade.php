@extends('layouts.petani-panel')
@use('App\Support\Fmt')
@section('title', 'Harga TBS')

@section('content')
<div class="page-head">
    <div>
        <h1>Harga TBS</h1>
        <p>Harga per kg untuk setiap periode. Dipakai untuk menghitung pendapatan panen. Daftar ini dipakai bersama oleh semua akun.</p>
    </div>
    <a href="{{ route('petani.harga-tbs.create') }}" class="btn btn-sawit"><i class="bi bi-plus-lg"></i> Tambah harga</a>
</div>

<div class="kartu stat mb-3" style="--a:#f2b632">
    <div class="lbl">Harga berlaku hari ini</div>
    @if ($hariIni)
        <div class="val">{{ Fmt::rp($hariIni->harga_per_kg) }} <span class="fs-6 fw-normal text-muted">/ kg</span></div>
        <div class="sub">Periode {{ Fmt::tgl($hariIni->periode_mulai) }} s/d {{ Fmt::tgl($hariIni->periode_selesai) }}</div>
    @else
        <div class="val text-muted">Belum ada harga yang berlaku</div>
        <div class="sub">Tambahkan harga dengan periode yang mencakup hari ini.</div>
    @endif
</div>

<div class="kartu p-0">
    @if ($harga->isEmpty())
        <div class="empty">
            <i class="bi bi-graph-up"></i>
            <div class="fw-semibold text-dark">Belum ada data harga TBS</div>
            <p class="mb-3">Tambahkan harga dulu supaya pendapatan panen bisa dihitung.</p>
            <a href="{{ route('petani.harga-tbs.create') }}" class="btn btn-sawit">Tambah harga</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Periode mulai</th>
                        <th>Periode selesai</th>
                        <th class="num">Harga/kg</th>
                        <th>Status</th>
                        <th class="num no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($harga as $h)
                        <tr>
                            <td>{{ Fmt::tgl($h->periode_mulai) }}</td>
                            <td>{{ Fmt::tgl($h->periode_selesai) }}</td>
                            <td class="num fw-semibold">{{ Fmt::rp($h->harga_per_kg) }}</td>
                            <td>
                                @if ($hariIni && $hariIni->id === $h->id)
                                    <span class="pill pill-green">Berlaku</span>
                                @elseif ($h->periode_mulai->gt(today()))
                                    <span class="pill pill-amber">Akan datang</span>
                                @else
                                    <span class="pill pill-gray">Lewat</span>
                                @endif
                            </td>
                            <td class="num no-print">
                                <div class="aksi">
                                    <a href="{{ route('petani.harga-tbs.edit', $h) }}" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('petani.harga-tbs.destroy', $h) }}" onsubmit="return confirm('Hapus harga TBS ini?')">
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

<div class="mt-3 no-print">{{ $harga->links('pagination::bootstrap-5') }}</div>
@endsection