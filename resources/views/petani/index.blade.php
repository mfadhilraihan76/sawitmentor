@extends('layouts.petani-panel')
@section('title', 'Manajemen Petani')
@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="mb-0">Manajemen Petani</h4>
            <small class="text-muted">Kelola data petani yang terdaftar.</small>
        </div>
        <a href="{{ route('petani.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Petani</a>
    </div>
    @include('partials.flash')
    <form method="GET" action="{{ route('petani.index') }}" class="d-flex gap-2 mb-3">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control w-auto" placeholder="Cari nama petani...">
        <button class="btn btn-dark">Cari</button>
        @if (request('q'))<a href="{{ route('petani.index') }}" class="btn btn-outline-secondary">Reset</a>@endif
    </form>
    <div class="card shadow-sm"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>No</th><th>Nama</th><th>No HP</th><th>Alamat</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($petani as $i => $p)
                <tr>
                    <td>{{ $petani->firstItem() + $i }}</td>
                    <td class="fw-semibold">{{ $p->nama }}</td>
                    <td>{{ $p->no_hp }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td class="text-center">
                        <a href="{{ route('petani.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('petani.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data petani.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>
    <div class="mt-3">{{ $petani->links() }}</div>
</div>
@endsection