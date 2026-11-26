@extends('layouts.auth')
@section('title', 'Daftar')
@section('side_title', 'Mulai catat panen dari hari ini.')
@section('side_text', 'Buat akun petani untuk melihat kebun, riwayat panen, dan laporan Anda.')

@section('content')
    <h2>Buat akun baru</h2>
    <p class="sub">Isi data berikut untuk mulai memakai SawitMentor.</p>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('register.proses') }}" novalidate>
        @csrf

        <div class="field">
            <label for="name">Nama lengkap</label>
            <div class="wrap">
                <i class="bi bi-person lead"></i>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Nama sesuai data kebun" autocomplete="name" required autofocus>
            </div>
            @error('name')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="email">Email</label>
            <div class="wrap">
                <i class="bi bi-envelope lead"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="nama@email.com" autocomplete="email" required>
            </div>
            @error('email')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="wrap">
                <i class="bi bi-lock lead"></i>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Buat password" autocomplete="new-password" required>
                <button type="button" class="toggle" data-target="password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>
            @error('password')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Ulangi password</label>
            <div class="wrap">
                <i class="bi bi-shield-lock lead"></i>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="Ketik ulang password" autocomplete="new-password" required>
                <button type="button" class="toggle" data-target="password_confirmation" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>
        </div>

        <button type="submit" class="btn-sawit mt-2">Buat akun</button>
    </form>

    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
@endsection