@extends('layouts.sawit_top')
@section('title', 'Profil Saya')
@section('warna', '#1f8f4e')

@section('content')
<div class="kartu p-4 mb-4">
    <h4 class="mb-1">Profil Saya</h4>
    <div class="text-muted">Kelola data akun, foto profil, dan password</div>
</div>
@include('petani._flash')

<div class="row g-3">
    <div class="col-lg-4">
        <div class="kartu p-4 text-center h-100">
            @if ($user->foto)
                <img class="avatar avatar-lg mb-3" src="{{ asset($user->foto) }}" alt="Foto profil">
            @else
                <span class="avatar avatar-lg mb-3">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
            @endif
            <h5 class="mb-0">{{ $user->name }}</h5>
            <div class="text-muted small mb-3">{{ $user->email }}</div>

            <form method="POST" action="{{ route('petani.profil.foto') }}" enctype="multipart/form-data" class="text-start">
                @csrf
                <label class="form-label small">Pasang foto baru (JPG, PNG, WEBP, maks. 2 MB)</label>
                <input type="file" name="foto" accept="image/png,image/jpeg,image/webp" class="form-control form-control-sm mb-2" required>
                <button class="btn btn-success btn-sm w-100">Unggah foto</button>
            </form>
            @if ($user->foto)
                <form method="POST" action="{{ route('petani.profil.foto.hapus') }}" class="mt-2" onsubmit="return confirm('Hapus foto profil?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm w-100">Hapus foto</button>
                </form>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        <div class="kartu p-4 mb-3">
            <h6>Data Akun</h6>
            <form method="POST" action="{{ route('petani.profil.update') }}" class="row g-3">
                @csrf
                <div class="col-md-6"><label class="form-label">Nama</label><input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">No. HP</label><input type="text" name="no_hp" value="{{ old('no_hp', ($petani && $petani->no_hp !== '-') ? $petani->no_hp : '') }}" class="form-control" maxlength="20"></div>
                <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" rows="2" class="form-control" maxlength="500">{{ old('alamat', ($petani && $petani->alamat !== '-') ? $petani->alamat : '') }}</textarea></div>
                <div class="col-12"><button class="btn btn-success">Simpan perubahan</button></div>
            </form>
        </div>

        <div class="kartu p-4" id="password">
            <h6>Ubah Password</h6>
            <form method="POST" action="{{ route('petani.profil.password') }}" class="row g-3">
                @csrf @method('PUT')
                <div class="col-12"><label class="form-label">Password lama</label><input type="password" name="password_lama" class="form-control @error('password_lama') is-invalid @enderror" autocomplete="current-password" required>@error('password_lama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Password baru (min. 8 karakter)</label><input type="password" name="password_baru" class="form-control @error('password_baru') is-invalid @enderror" autocomplete="new-password" minlength="8" required>@error('password_baru')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Ulangi password baru</label><input type="password" name="password_baru_confirmation" class="form-control" autocomplete="new-password" minlength="8" required></div>
                <div class="col-12"><button class="btn btn-success">Ubah password</button></div>
            </form>
        </div>
    </div>
</div>
@endsection