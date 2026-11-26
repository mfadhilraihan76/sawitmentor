@extends('layouts.sawit_top')
@section('title', 'Tambah Panen')
@section('warna', '#1f8f4e')

@section('content')
<div class="kartu p-4 mb-4">
    <h4 class="mb-1">Tambah Panen</h4>
    <div class="text-muted">Catat hasil panen kebun Anda. Pendapatan dihitung otomatis dari harga TBS yang berlaku pada tanggal panen.</div>
</div>
@include('petani._flash')
<div class="kartu p-4" style="max-width:640px">
    @if ($kebun->isEmpty())
        <div class="text-center text-muted py-3">
            <p>Anda belum memiliki kebun, tambahkan kebun terlebih dahulu.</p>
            <a href="{{ route('petani.kebun.create') }}" class="btn btn-success">Tambah Kebun</a>
        </div>
    @else
        <div class="small text-muted mb-3">
            Harga TBS hari ini:
            @if ($hargaHariIni) <strong>Rp{{ number_format($hargaHariIni->harga_per_kg, 0, ',', '.') }}/kg</strong>
            @else belum ada harga berlaku @endif
        </div>
        <form method="POST" action="{{ route('petani.panen.store') }}">
            @csrf
            <div class="mb-3"><label class="form-label">Kebun</label>
                <select name="kebun_id" class="form-select" required>
                    @foreach ($kebun as $k)<option value="{{ $k->id }}" @selected(old('kebun_id') == $k->id)>{{ $k->nama_kebun }}</option>@endforeach
                </select></div>
            <div class="mb-3"><label class="form-label">Tanggal panen</label><input type="date" name="tanggal_panen" value="{{ old('tanggal_panen', now()->toDateString()) }}" max="{{ now()->toDateString() }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Berat (kg)</label><input type="number" step="0.01" min="0.01" name="berat_kg" value="{{ old('berat_kg') }}" class="form-control" required></div>
            <div class="d-flex gap-2">
                <button class="btn btn-success">Simpan</button>
                <a href="{{ route('petani.kebun') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    @endif
</div>
@endsection