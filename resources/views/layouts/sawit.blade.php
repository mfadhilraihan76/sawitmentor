<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SawitMentor')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --warna: @yield('warna', '#7367f0'); }
        body { background:#f5f5f9; }
        .side { width:250px; min-height:100vh; background:#fff; box-shadow:0 0 12px rgba(0,0,0,.06); }
        .side .logo { width:100%; height:auto; }
        .side a.nav-link { color:#555; border-radius:8px; margin-bottom:4px; }
        .side a.nav-link.active, .side a.nav-link:hover { background:var(--warna); color:#fff; }
        .hero { background:var(--warna); color:#fff; border-radius:12px; }
        .kartu { background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.06); }
        @media print {
            .side, .no-print { display:none !important; }
            body { background:#fff; }
            .kartu { box-shadow:none; }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <aside class="side p-3">
        <div class="mb-4"><img class="logo" src="{{ asset('images/logo.png') }}" alt="SawitMentor"></div>
        <nav class="nav flex-column">
            @yield('menu')
        </nav>
    </aside>
    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <span class="badge" style="background:var(--warna)">{{ strtoupper(auth()->user()->role) }}</span>
            <div class="d-flex align-items-center gap-3">
                <span><i class="bi bi-person-circle"></i> {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </div>
        @yield('content')
    </main>
</div>
</body>
</html>