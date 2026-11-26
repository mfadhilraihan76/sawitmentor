@extends('layouts.sawit_top')
@section('title', $item->exists ? 'Edit laporan' : 'Buat laporan')

@section('content')
@include('petani._panel-style')
<div class="page-head">
    <div>
        <h1>{{ $item->exists ? 'Edit laporan' : 'Buat laporan' }}</h1>
        <p>Isi data panen dan keuangan pada periode ini akan dirangkum otomatis.</p>
    </div>
</div>

<div class="kartu form-card" style="--a:#0d9bb5">
    <form method="POST" action="{{ $item->exists ? route('petani.laporan.update', $item) : route('petani.laporan.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label" for="judul">Judul laporan</label>
            <input type="text" name="judul" id="judul"
                   value="{{ old('judul', $item->judul ?: 'Laporan ' . now()->translatedFormat('F Y')) }}"
                   class="form-control @error('judul') is-invalid @enderror" required autofocus>
            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" for="dari">Dari tanggal</label>
                <input type="date" name="dari" id="dari" value="{{ old('dari', $item->dari?->format('Y-m-d')) }}"
                       class="form-control @error('dari') is-invalid @enderror" required>
                @error('dari')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="sampai">Sampai tanggal</label>
                <input type="date" name="sampai" id="sampai" value="{{ old('sampai', $item->sampai?->format('Y-m-d')) }}"
                       class="form-control @error('sampai') is-invalid @enderror" required>
                @error('sampai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label" for="catatan">Catatan (opsional)</label>
            <textarea name="catatan" id="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror"
                      placeholder="Catatan yang ikut tercetak di laporan">{{ old('catatan', $item->catatan) }}</textarea>
            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sawit">{{ $item->exists ? 'Simpan' : 'Buat laporan' }}</button>
            <a href="{{ route('petani.laporan') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection