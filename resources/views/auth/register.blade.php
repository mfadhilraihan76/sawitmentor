<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - SawitMentor</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f5f5f9">
<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh">
    <div class="card shadow-sm border-0" style="width:460px">
        <div class="card-body p-4">
            <div class="text-center mb-3"><img src="{{ asset('images/logo.png') }}" alt="SawitMentor" style="max-width:100%;height:80px;object-fit:contain"></div>
            <p class="text-muted text-center">Buat akun baru</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.proses') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ulangi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="petani" @selected(old('role') === 'petani')>Petani</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    </select>
                </div>
                <button class="btn btn-primary w-100">Daftar</button>
            </form>
            <p class="text-center mt-3 mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>