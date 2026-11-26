@php
    $on = fn (...$pola) => request()->routeIs(...$pola) ? 'active' : '';
    $nama = auth()->user()->name ?? 'Pengguna';
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SawitMentor</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sawit:#1f8f4e; --sawit-dark:#17703d; --ink:#16241b; --muted:#6a776f; --line:#e2e9e3; --gold:#f2b632; }
        body { font-family:'Plus Jakarta Sans',system-ui,sans-serif; color:var(--ink); font-size:.9rem; min-height:100vh;
               background:linear-gradient(135deg,#e6f5ec 0%,#f7faf7 45%,#fff3de 100%) fixed; }
        .wrap-page { max-width:1200px; margin:0 auto; padding:0 1rem; }

        /* ===== Navbar ===== */
        .topbar { background:#fff; border-bottom:3px solid; border-image:linear-gradient(90deg,#1f8f4e,#f2b632,#7c5cd6,#e0485a,#1f8f4e) 1; padding:.4rem 0; }
        .nav-chip { display:inline-flex; align-items:center; gap:.45rem; padding:.4rem .65rem; border-radius:8px; color:var(--ink);
                    text-decoration:none; font-weight:500; font-size:.85rem; }
        .nav-chip:hover { background:#f1f5f2; color:var(--ink); }
        .nav-chip.active { background:var(--sawit); color:#fff; }
        .chip-ic { width:22px; height:22px; border-radius:6px; display:inline-grid; place-items:center; font-size:.75rem;
                   background:color-mix(in srgb,var(--c) 15%,#fff); color:var(--c); }
        .nav-chip.active .chip-ic { background:rgba(255,255,255,.22); color:#fff; }
        .dropdown-menu { border:1px solid var(--line); border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.08); font-size:.85rem; }
        .dropdown-item { border-radius:6px; }
        .dropdown-item.active, .dropdown-item:active { background:var(--sawit); color:#fff; }
        .avatar { width:30px; height:30px; border-radius:50%; background:var(--sawit); color:#fff; display:inline-grid; place-items:center; font-weight:700; font-size:.8rem; }

        /* ===== Komponen ===== */
        .page-head { display:flex; flex-wrap:wrap; gap:1rem; justify-content:space-between; align-items:center; margin-bottom:1.25rem; }
        .page-head h1 { font-size:1.35rem; font-weight:700; margin:0; }
        .page-head p { margin:.15rem 0 0; color:var(--muted); }
        .kartu { background:#fff; border:1px solid var(--line); border-top:3px solid var(--a,var(--sawit)); border-radius:12px; padding:1.1rem 1.25rem; box-shadow:0 1px 3px rgba(20,40,25,.04); }
        .kartu.p-0 { padding:0; }
        .stat .lbl { color:var(--muted); font-size:.78rem; }
        .stat .val { font-size:1.35rem; font-weight:700; line-height:1.3; }
        .tbl { width:100%; border-collapse:collapse; }
        .tbl th { background:#e8f4ec; font-weight:600; font-size:.8rem; padding:.6rem .75rem; text-align:left; white-space:nowrap; }
        .tbl td { padding:.65rem .75rem; border-bottom:1px solid var(--line); vertical-align:middle; }
        .tbl tbody tr:last-child td { border-bottom:0; }
        .tbl .num { text-align:right; }
        .tbl tfoot td { font-weight:700; background:#f6faf7; border-top:2px solid var(--line); }
        .sub { color:var(--muted); font-size:.8rem; }
        .btn-sawit { background:var(--sawit); color:#fff; border:0; border-radius:9px; padding:.5rem 1rem; font-weight:600; }
        .btn-sawit:hover { background:var(--sawit-dark); color:#fff; }
        .btn-sawit:focus-visible, .btn-icon:focus-visible { outline:3px solid rgba(242,182,50,.7); outline-offset:2px; }
        .btn-icon { width:32px; height:32px; display:inline-grid; place-items:center; border-radius:8px; border:1px solid var(--line); background:#fff; color:var(--ink); text-decoration:none; padding:0; }
        .btn-icon:hover { background:#f1f5f2; }
        .btn-icon.hapus:hover { background:#fdecec; color:#c0392b; border-color:#f3c4c0; }
        .btn-icon.ok:hover { background:#e6f6ec; color:var(--sawit-dark); border-color:#bfe3cc; }
        .aksi { display:inline-flex; gap:.35rem; }
        .pill { display:inline-block; padding:.15rem .55rem; border-radius:999px; font-size:.75rem; font-weight:600; }
        .pill-green { background:#e3f5ea; color:#17703d; } .pill-red { background:#fdecec; color:#b3382c; }
        .pill-amber { background:#fff3d6; color:#9a6a00; } .pill-gray { background:#eef1ef; color:#56635b; }
        .filter { display:flex; flex-wrap:wrap; gap:.5rem; align-items:end; margin-bottom:1rem; }
        .filter .form-control, .filter .form-select { width:auto; min-width:150px; }
        .filter label { display:block; font-size:.75rem; color:var(--muted); margin-bottom:.2rem; }
        .empty { text-align:center; padding:2.75rem 1rem; color:var(--muted); }
        .empty i { font-size:2.2rem; color:var(--sawit); display:block; margin-bottom:.5rem; }
        .form-card { max-width:640px; }
        .form-label { font-weight:600; font-size:.82rem; }
        .form-control, .form-select { border-radius:9px; border:1.5px solid var(--line); padding:.55rem .75rem; }
        .form-control:focus, .form-select:focus { border-color:var(--sawit); box-shadow:0 0 0 4px rgba(31,143,78,.14); }
        .input-group > .form-control { border-radius:0 9px 9px 0; } .input-group > .form-control:first-child { border-radius:9px 0 0 9px; }
        .input-group-text { border:1.5px solid var(--line); background:#f6faf7; }
        .invalid-feedback { display:block; }
        .alert { border:0; border-radius:10px; }

        @media print {
            .no-print { display:none !important; }
            body { background:#fff; font-size:.8rem; }
            .kartu { box-shadow:none; border:1px solid #cfd8d1; border-top-width:1px; break-inside:avoid; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="topbar navbar navbar-expand-lg no-print">
    <div class="wrap-page d-flex flex-wrap align-items-center w-100">
        <a class="navbar-brand me-3" href="{{ route('petani.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="SawitMentor" height="30">
        </a>
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama"
                aria-controls="menuUtama" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuUtama">
            <ul class="navbar-nav mx-lg-auto gap-lg-1">
                <li class="nav-item">
                    <a class="nav-chip {{ $on('petani.dashboard') }}" href="{{ route('petani.dashboard') }}">
                        <span class="chip-ic" style="--c:#1f8f4e"><i class="bi bi-speedometer2"></i></span> Dashboard saya
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-chip dropdown-toggle {{ $on('petani.kebun*', 'petani.panen*') }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="chip-ic" style="--c:#2f6db5"><i class="bi bi-flower1"></i></span> Kebun
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ $on('petani.kebun*') }}" href="{{ route('petani.kebun') }}"><i class="bi bi-geo-alt me-2"></i>Data kebun</a></li>
                        <li><a class="dropdown-item {{ $on('petani.panen*') }}" href="{{ route('petani.panen.index') }}"><i class="bi bi-basket me-2"></i>Hasil panen</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-chip dropdown-toggle {{ $on('petani.keuangan*', 'petani.harga-tbs*') }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="chip-ic" style="--c:#e08a00"><i class="bi bi-wallet2"></i></span> Keuangan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ $on('petani.keuangan*') }}" href="{{ route('petani.keuangan.index') }}"><i class="bi bi-cash-stack me-2"></i>Pemasukan &amp; pengeluaran</a></li>
                        <li><a class="dropdown-item {{ $on('petani.harga-tbs*') }}" href="{{ route('petani.harga-tbs.index') }}"><i class="bi bi-graph-up me-2"></i>Harga TBS</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-chip dropdown-toggle {{ $on('petani.perawatan*') }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="chip-ic" style="--c:#7c5cd6"><i class="bi bi-tools"></i></span> Perawatan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ $on('petani.perawatan*') }}" href="{{ route('petani.perawatan.index') }}"><i class="bi bi-calendar-check me-2"></i>Jadwal perawatan</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-chip" href="#"><span class="chip-ic" style="--c:#e0485a"><i class="bi bi-bell"></i></span> Notifikasi</a>
                </li>

                <li class="nav-item">
                    <a class="nav-chip {{ $on('petani.laporan*') }}" href="{{ route('petani.laporan') }}">
                        <span class="chip-ic" style="--c:#0d9bb5"><i class="bi bi-file-earmark-text"></i></span> Laporan saya
                    </a>
                </li>
            </ul>

            <div class="dropdown ms-lg-3">
                <a class="nav-chip dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar">{{ strtoupper(mb_substr($nama, 0, 1)) }}</span> {{ $nama }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<main class="wrap-page py-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show no-print" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>