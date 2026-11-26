@extends('layouts.petani-panel')
@use('App\Support\Fmt')
@section('title', 'Laporan saya')

@section('content')
<div class="page-head">
    <div>
        <h1>Laporan saya</h1>
        <p>Buat laporan per periode berisi ringkasan panen dan keuangan, lalu cetak kapan saja.</p>
    </div>
    <a href="{{ route('petani.laporan.create') }}" class="btn btn-sawit"><i class="bi bi-plus-lg"></i> Buat laporan</a>
</div>

<div class="kartu p-0" style="--a:#0d9bb5">
    @if ($laporan->isEmpty())
        <div class="empty">
            <i class="bi bi-file-earmark-text" style="color:#0d9bb5"></i>
            <div class="fw-semibold text-dark">Belum ada laporan</div>
            <p class="mb-3">Pilih periode dan sistem akan merangkum panen serta keuangan Anda.</p>
            <a href="{{ route('petani.laporan.create') }}" class="btn btn-sawit">Buat laporan</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Periode</th>
                        <th>Dibuat</th>
                        <th class="num">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $l)
                        <tr>
                            <td>
                                <a href="{{ route('petani.laporan.show', $l) }}" class="fw-semibold text-decoration-none">{{ $l->judul }}</a>
                                @if ($l->catatan)<div class="sub">{{ \Illuminate\Support\Str::limit($l->catatan, 80) }}</div>@endif
                            </td>
                            <td>{{ Fmt::tgl($l->dari) }} s/d {{ Fmt::tgl($l->sampai) }}</td>
                            <td>{{ Fmt::tgl($l->created_at) }}</td>
                            <td class="num">
                                <div class="aksi">
                                    <a href="{{ route('petani.laporan.show', $l) }}" class="btn-icon" title="Lihat dan cetak"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('petani.laporan.edit', $l) }}" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('petani.laporan.destroy', $l) }}" onsubmit="return confirm('Hapus laporan ini?')">
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

<div class="mt-3">{{ $laporan->links('pagination::bootstrap-5') }}</div>
@endsection