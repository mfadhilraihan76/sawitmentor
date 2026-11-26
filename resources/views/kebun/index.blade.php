@extends('layouts.petani-panel')
@section('title', 'Manajemen Kebun')
@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="mb-0">Manajemen Kebun</h4>
            <small class="text-muted">Daftar kebun milik petani.</small>
        </div>
        <a href="{{ route('kebun.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Kebun</a>
    </div>
    @include('partials.flash')
    <form method="GET" action="{{ route('kebun.index') }}" class="d-flex gap-2 mb-3">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control w-auto" placeholder="Cari nama kebun...">
        <button class="btn btn-dark">Cari</button>
        @if (request('q'))<a href="{{ route('kebun.index') }}" class="btn btn-outline-secondary">Reset</a>@endif
    </form>
    <div class="card shadow-sm"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>No</th><th>Nama Kebun</th><th>Petani</th><th>Lokasi</th><th class="text-end">Luas (ha)</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($kebun as $i => $k)
                <tr>
                    <td>{{ $kebun->firstItem() + $i }}</td>
                    <td class="fw-semibold">{{ $k->nama_kebun }}</td>
                    <td>{{ $k->petani->nama ?? '-' }}</td>
                    <td>{{ $k->lokasi }}</td>
                    <td class="text-end">{{ number_format($k->luas_lahan, 2, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="{{ route('kebun.edit', $k) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('kebun.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kebun ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data kebun.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>
    <div class="mt-3">{{ $kebun->links() }}</div>
</div>
@endsection