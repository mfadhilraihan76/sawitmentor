@extends('layouts.petani-panel')
@use('App\Models\Perawatan')
@section('title', $item->exists ? 'Edit jadwal perawatan' : 'Tambah jadwal perawatan')

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $item->exists ? 'Edit jadwal perawatan' : 'Tambah jadwal perawatan' }}</h1>
        <p>Tentukan jenis perawatan, tanggal, dan kebun yang dirawat.</p>
    </div>
</div>

<div class="kartu form-card" style="--a:#7c5cd6">
    <form method="POST" action="{{ $item->exists ? route('petani.perawatan.update', $item) : route('petani.perawatan.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label" for="jenis">Jenis perawatan</label>
            <select name="jenis" id="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                <option value="" disabled @selected(! old('jenis', $item->jenis))>Pilih jenis</option>
                @foreach (Perawatan::JENIS as $j)
                    <option value="{{ $j }}" @selected(old('jenis', $item->jenis) === $j)>{{ $j }}</option>
                @endforeach
            </select>
            @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="kebun_id">Kebun</label>
            <select name="kebun_id" id="kebun_id" class="form-select @error('kebun_id') is-invalid @enderror">
                <option value="">Semua kebun</option>
                @foreach ($kebun as $k)
                    <option value="{{ $k->id }}" @selected(old('kebun_id', $item->kebun_id) == $k->id)>{{ $k->nama_kebun }}</option>
                @endforeach
            </select>
            @error('kebun_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $item->tanggal?->format('Y-m-d')) }}"
                       class="form-control @error('tanggal') is-invalid @enderror" required>
                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="terjadwal" @selected(old('status', $item->status ?: 'terjadwal') === 'terjadwal')>Terjadwal</option>
                    <option value="selesai" @selected(old('status', $item->status) === 'selesai')>Selesai</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label" for="catatan">Catatan (opsional)</label>
            <textarea name="catatan" id="catatan" rows="2" class="form-control @error('catatan') is-invalid @enderror"
                      placeholder="Contoh: Pupuk NPK 2 kg per pohon">{{ old('catatan', $item->catatan) }}</textarea>
            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sawit">Simpan</button>
            <a href="{{ route('petani.perawatan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection