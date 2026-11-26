@extends('layouts.sawit_top')
@section('title', 'Tambah Kebun')
@section('warna', '#1f8f4e')

@section('content')
<div class="kartu p-4 mb-4">
    <h4 class="mb-1">Tambah Kebun</h4>
    <div class="text-muted">Daftarkan kebun milik Anda</div>
</div>
@include('petani._flash')
<div class="kartu p-4" style="max-width:640px">
    <form method="POST" action="{{ route('petani.kebun.store') }}">
        @csrf
        <div class="mb-3"><label class="form-label">Nama kebun</label><input type="text" name="nama_kebun" value="{{ old('nama_kebun') }}" class="form-control" maxlength="255" required></div>
        <div class="mb-3"><label class="form-label">Lokasi</label><input type="text" name="lokasi" value="{{ old('lokasi') }}" class="form-control" maxlength="255" required></div>
        <div class="mb-3"><label class="form-label">Luas lahan (hektar)</label><input type="number" step="0.01" min="0.01" name="luas_lahan" value="{{ old('luas_lahan') }}" class="form-control" required></div>
        <div class="d-flex gap-2">
            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('petani.kebun') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection