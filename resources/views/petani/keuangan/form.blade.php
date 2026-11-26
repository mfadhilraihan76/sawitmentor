@extends('layouts.sawit_top')
@use('App\Models\Keuangan')
@section('title', $item->exists ? 'Edit transaksi' : 'Tambah transaksi')

@section('content')
@include('petani._panel-style')
@php $jenis = old('jenis', $item->jenis ?: 'pengeluaran'); @endphp
<div class="page-head">
    <div>
        <h1>{{ $item->exists ? 'Edit transaksi' : 'Tambah transaksi' }}</h1>
        <p>Catat pengeluaran kebun atau pemasukan di luar hasil panen.</p>
    </div>
</div>

<div class="kartu form-card">
    <form method="POST" action="{{ $item->exists ? route('petani.keuangan.update', $item) : route('petani.keuangan.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label d-block">Jenis transaksi</label>
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="jenis" id="jenis_keluar" value="pengeluaran" @checked($jenis === 'pengeluaran')>
                <label class="btn btn-outline-danger" for="jenis_keluar">Pengeluaran</label>
                <input type="radio" class="btn-check" name="jenis" id="jenis_masuk" value="pemasukan" @checked($jenis === 'pemasukan')>
                <label class="btn btn-outline-success" for="jenis_masuk">Pemasukan</label>
            </div>
            @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                    <option value="" disabled @selected(! old('kategori', $item->kategori))>Pilih kategori</option>
                    @foreach (Keuangan::KATEGORI as $kat)
                        <option value="{{ $kat }}" @selected(old('kategori', $item->kategori) === $kat)>{{ $kat }}</option>
                    @endforeach
                </select>
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $item->tanggal?->format('Y-m-d')) }}"
                       class="form-control @error('tanggal') is-invalid @enderror" required>
                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="jumlah">Jumlah</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" step="1" min="1" name="jumlah" id="jumlah"
                       value="{{ old('jumlah', $item->exists ? (float) $item->jumlah : '') }}"
                       class="form-control @error('jumlah') is-invalid @enderror" placeholder="0" required>
            </div>
            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="kebun_id">Kebun (opsional)</label>
            <select name="kebun_id" id="kebun_id" class="form-select @error('kebun_id') is-invalid @enderror">
                <option value="">Tidak terkait kebun tertentu</option>
                @foreach ($kebun as $k)
                    <option value="{{ $k->id }}" @selected(old('kebun_id', $item->kebun_id) == $k->id)>{{ $k->nama_kebun }}</option>
                @endforeach
            </select>
            @error('kebun_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label" for="keterangan">Keterangan (opsional)</label>
            <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror"
                      placeholder="Contoh: Pupuk NPK 5 karung">{{ old('keterangan', $item->keterangan) }}</textarea>
            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sawit">Simpan</button>
            <a href="{{ route('petani.keuangan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection