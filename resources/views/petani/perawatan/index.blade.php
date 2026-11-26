@extends('layouts.sawit_top')
@use('App\Support\Fmt')
@section('title', 'Jadwal perawatan')

@section('content')
@include('petani._panel-style')
<div class="page-head">
    <div>
        <h1>Jadwal perawatan</h1>
        <p>Atur jadwal pemupukan, penyemprotan, dan perawatan kebun lainnya.</p>
    </div>
    <a href="{{ route('petani.perawatan.create') }}" class="btn btn-sawit"><i class="bi bi-plus-lg"></i> Tambah jadwal</a>
</div>

<form method="GET" action="{{ route('petani.perawatan.index') }}" class="filter no-print">
    <div>
        <label for="status">Status</label>
        <select name="status" id="status" class="form-select">
            <option value="">Semua</option>
            <option value="terjadwal" @selected(request('status') === 'terjadwal')>Terjadwal</option>
            <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
        </select>
    </div>
    <button class="btn btn-dark">Terapkan</button>
    @if (request('status'))
        <a href="{{ route('petani.perawatan.index') }}" class="btn btn-outline-secondary">Reset</a>
    @endif
</form>

<div class="kartu p-0" style="--a:#7c5cd6">
    @if ($perawatan->isEmpty())
        <div class="empty">
            <i class="bi bi-calendar-check" style="color:#7c5cd6"></i>
            <div class="fw-semibold text-dark">Belum ada jadwal perawatan</div>
            <p class="mb-3">Buat jadwal pertama supaya perawatan kebun tidak terlewat.</p>
            <a href="{{ route('petani.perawatan.create') }}" class="btn btn-sawit">Tambah jadwal</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kebun</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th class="num no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perawatan as $p)
                        <tr>
                            <td>{{ Fmt::tgl($p->tanggal) }}</td>
                            <td class="fw-semibold">{{ $p->jenis }}</td>
                            <td>{{ $p->kebun->nama_kebun ?? 'Semua kebun' }}</td>
                            <td>
                                @if ($p->status === 'selesai')
                                    <span class="pill pill-green">Selesai</span>
                                @elseif ($p->tanggal->lt(today()))
                                    <span class="pill pill-red">Terlambat</span>
                                @else
                                    <span class="pill pill-amber">Terjadwal</span>
                                @endif
                            </td>
                            <td class="sub">{{ $p->catatan ?: '-' }}</td>
                            <td class="num no-print">
                                <div class="aksi">
                                    @if ($p->status !== 'selesai')
                                        <form method="POST" action="{{ route('petani.perawatan.selesai', $p) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn-icon ok" title="Tandai selesai"><i class="bi bi-check2"></i></button>
                                        </form>
                                    @endif
                                    <a href="{{ route('petani.perawatan.edit', $p) }}" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('petani.perawatan.destroy', $p) }}" onsubmit="return confirm('Hapus jadwal ini?')">
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

<div class="mt-3 no-print">{{ $perawatan->links('pagination::bootstrap-5') }}</div>
@endsection