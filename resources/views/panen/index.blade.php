@extends('layouts.petani-panel')
@section('title', 'Data Panen')
@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="mb-0">Data Panen</h4>
            <small class="text-muted">Riwayat hasil panen dan pendapatan.</small>
        </div>
        <a href="{{ route('panen.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Catat Panen</a>
    </div>
    @include('partials.flash')
    <form method="GET" action="{{ route('panen.index') }}" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small mb-1">Kebun</label>
            <select name="kebun_id" class="form-select">
                <option value="">Semua kebun</option>
                @foreach ($kebun as $k)
                    <option value="{{ $k->id }}" @selected(request('kebun_id') == $k->id)>{{ $k->nama_kebun }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><label class="form-label small mb-1">Dari</label><input type="date" name="dari" value="{{ request('dari') }}" class="form-control"></div>
        <div class="col-md-2"><label class="form-label small mb-1">Sampai</label><input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control"></div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-dark">Filter</button>
            <a href="{{ route('panen.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
    <div class="card shadow-sm"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Tanggal</th><th>Petani</th><th>Kebun</th><th class="text-end">Berat (kg)</th><th class="text-end">Harga/kg</th><th class="text-end">Pendapatan</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($panen as $p)
                <tr>
                    <td>{{ $p->tanggal_panen->format('d/m/Y') }}</td>
                    <td>{{ $p->kebun->petani->nama ?? '-' }}</td>
                    <td>{{ $p->kebun->nama_kebun ?? '-' }}</td>
                    <td class="text-end">{{ number_format($p->berat_kg, 0, ',', '.') }}</td>
                    <td class="text-end">Rp{{ number_format($p->hargaTbs->harga_per_kg ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end fw-semibold">Rp{{ number_format($p->total_pendapatan, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="{{ route('panen.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('panen.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data panen ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data panen.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>
    <div class="mt-3">{{ $panen->links() }}</div>
</div>
@endsection