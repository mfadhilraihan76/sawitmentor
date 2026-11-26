@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
    <h2>Masuk ke akun Anda</h2>
    <p class="sub">Lanjutkan memantau kebun dan hasil panen Anda.</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.proses') }}" novalidate>
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <div class="wrap">
                <i class="bi bi-envelope lead"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="nama@email.com" autocomplete="email" required autofocus>
            </div>
            @error('email')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="wrap">
                <i class="bi bi-lock lead"></i>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Masukkan password" autocomplete="current-password" required>
                <button type="button" class="toggle" data-target="password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>
            @error('password')<div class="err">{{ $message }}</div>@enderror
        </div>

        <label class="check">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            Ingat saya
        </label>

        <button type="submit" class="btn-sawit">Masuk</button>
    </form>

    <p class="switch">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
@endsection