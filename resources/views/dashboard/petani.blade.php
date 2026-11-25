<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Petani - SawitMentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f1f8f1; }
        .sidebar { width:230px; min-height:100vh; background:#1b5e20; color:#fff; }
        .sidebar a { color:#e8f5e9; text-decoration:none; display:block; padding:10px 20px; border-radius:8px; margin:4px 10px; }
        .sidebar a:hover, .sidebar a.active { background:#2e7d32; }
        .card-stat { border:0; border-left:5px solid #43a047; box-shadow:0 2px 8px rgba(0,0,0,.06); }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar py-3">
        <h5 class="px-3 mb-4">🌴 SawitMentor</h5>
        <a href="#" class="active">Dashboard</a>
        <a href="#">Kebun Saya</a>
        <a href="#">Panen Saya</a>
        <a href="#">Harga TBS</a>
    </div>

    <div class="flex-grow-1">
        <nav class="navbar bg-white shadow-sm px-4">
            <span class="fw-semibold">Dashboard Petani</span>
            <div class="dropdown">
                <a class="dropdown-toggle text-decoration-none text-dark" href="#" data-bs-toggle="dropdown">
                    {{ auth()->user()->name }} <small class="text-muted">({{ ucfirst(auth()->user()->role) }})</small>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">My Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="px-3">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="p-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Halo, {{ auth()->user()->name }} 👋</h4>
                        <small class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</small>
                    </div>
                    <div>
                        <a href="#" class="btn btn-success">Tambah Panen</a>
                        <a href="#" class="btn btn-outline-success">Kebun Saya</a>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3"><div class="card card-stat"><div class="card-body"><small class="text-muted">Total Panen Saya</small><h3>0 kg</h3></div></div></div>
                <div class="col-md-3"><div class="card card-stat"><div class="card-body"><small class="text-muted">Pendapatan Saya</small><h3>Rp0</h3></div></div></div>
                <div class="col-md-3"><div class="card card-stat"><div class="card-body"><small class="text-muted">Jumlah Kebun Saya</small><h3>0</h3></div></div></div>
                <div class="col-md-3"><div class="card card-stat"><div class="card-body"><small class="text-muted">Harga TBS Hari Ini</small><h3>Rp0</h3></div></div></div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5>Panen Terbaru Saya</h5>
                    <p class="text-muted mb-0">Belum ada data panen.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>