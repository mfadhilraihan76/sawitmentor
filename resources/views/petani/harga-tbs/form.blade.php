@extends('layouts.sawit_top')
@section('title', $item->exists ? 'Edit harga TBS' : 'Tambah harga TBS')

@section('content')
@include('petani._panel-style')
<div class="page-head">
    <div>
        <h1>{{ $item->exists ? 'Edit harga TBS' : 'Tambah harga TBS' }}</h1>
        <p>Periode tidak boleh tumpang tindih dengan harga yang sudah ada.</p>
    </div>
</div>

<div class="kartu form-card" style="--a:#f2b632">
    <form method="POST" action="{{ $item->exists ? route('petani.harga-tbs.update', $item) : route('petani.harga-tbs.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" for="periode_mulai">Periode mulai</label>
                <input type="date" name="periode_mulai" id="periode_mulai" value="{{ old('periode_mulai', $item->periode_mulai?->format('Y-m-d')) }}"
                       class="form-control @error('periode_mulai') is-invalid @enderror" required>
                @error('periode_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="periode_selesai">Periode selesai</label>
                <input type="date" name="periode_selesai" id="periode_selesai" value="{{ old('periode_selesai', $item->periode_selesai?->format('Y-m-d')) }}"
                       class="form-control @error('periode_selesai') is-invalid @enderror" required>
                @error('periode_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label" for="harga_per_kg">Harga per kg</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" step="1" min="1" name="harga_per_kg" id="harga_per_kg"
                       value="{{ old('harga_per_kg', $item->exists ? (float) $item->harga_per_kg : '') }}"
                       class="form-control @error('harga_per_kg') is-invalid @enderror" placeholder="0" required>
                <span class="input-group-text">/ kg</span>
            </div>
            @error('harga_per_kg')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sawit">Simpan</button>
            <a href="{{ route('petani.harga-tbs.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection