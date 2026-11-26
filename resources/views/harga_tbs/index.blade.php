@extends('layouts.petani-panel')
@section('title', 'Harga TBS')
@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="mb-0">Harga TBS</h4>
            <small class="text-muted">Harga per kg untuk setiap periode. Dipakai untuk menghitung pendapatan panen.</small>
        </div>
        <a href="{{ route('harga-tbs.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Harga</a>
    </div>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Periode Mulai</th><th>Periode Selesai</th><th class="text-end">Harga/kg</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($harga as $h)
                <tr>
                    <td>{{ $h->periode_mulai->format('d/m/Y') }}</td>
                    <td>{{ $h->periode_selesai->format('d/m/Y') }}</td>
                    <td class="text-end fw-semibold">Rp{{ number_format($h->harga_per_kg, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="{{ route('harga-tbs.edit', $h) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('harga-tbs.destroy', $h) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus harga ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data harga TBS.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>
    <div class="mt-3">{{ $harga->links() }}</div>
</div>
@endsection