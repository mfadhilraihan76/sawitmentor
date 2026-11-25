<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SawitMentor</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f5f5f9">
<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh">
    <div class="card shadow-sm border-0" style="width:440px">
        <div class="card-body p-4">
            <div class="text-center mb-3"><img src="{{ asset('images/logo.png') }}" alt="SawitMentor" style="max-width:100%;height:80px;object-fit:contain"></div>
            <p class="text-muted text-center">Silakan login ke akunmu</p>

            @if (session('status'))
                <div class="alert alert-success py-2">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.proses') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label for="remember" class="form-check-label">Ingat saya</label>
                </div>
                <button class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
        </div>
    </div>
</div>
</body>
</html>