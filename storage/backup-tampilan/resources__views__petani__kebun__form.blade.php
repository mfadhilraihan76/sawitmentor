@extends('layouts.petani-panel')
@section('title', $item->exists ? 'Edit kebun' : 'Tambah kebun')

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $item->exists ? 'Edit kebun' : 'Tambah kebun' }}</h1>
        <p>Isi data kebun sesuai kondisi di lapangan.</p>
    </div>
</div>

<div class="kartu form-card">
    <form method="POST" action="{{ $item->exists ? route('petani.kebun.update', $item) : route('petani.kebun.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label" for="nama_kebun">Nama kebun</label>
            <input type="text" id="nama_kebun" name="nama_kebun" value="{{ old('nama_kebun', $item->nama_kebun) }}"
                   class="form-control @error('nama_kebun') is-invalid @enderror" placeholder="Contoh: Kebun Blok A" required autofocus>
            @error('nama_kebun')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}"
                   class="form-control @error('lokasi') is-invalid @enderror" placeholder="Desa / kecamatan / patokan" required>
            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label" for="luas_lahan">Luas lahan</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0.01" id="luas_lahan" name="luas_lahan"
                       value="{{ old('luas_lahan', $item->exists ? (float) $item->luas_lahan : '') }}"
                       class="form-control @error('luas_lahan') is-invalid @enderror" required>
                <span class="input-group-text">hektar</span>
            </div>
            @error('luas_lahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sawit">Simpan</button>
            <a href="{{ route('petani.kebun') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection