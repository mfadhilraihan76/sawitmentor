@extends('layouts.sawit')
@section('title', 'Dashboard Admin')
@section('warna', '#7367f0')

@section('menu')
    <a class="nav-link active" href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a>
    <small class="text-muted mt-3 mb-1">DATA MASTER</small>
    <a class="nav-link" href="{{ Route::has('petani.index') ? route('petani.index') : '#' }}"><i class="bi bi-person"></i> Petani</a>
    <a class="nav-link" href="{{ Route::has('kebun.index') ? route('kebun.index') : '#' }}"><i class="bi bi-flower1"></i> Kebun</a>
    <a class="nav-link" href="{{ Route::has('panen.index') ? route('panen.index') : '#' }}"><i class="bi bi-basket"></i> Panen</a>
    <a class="nav-link" href="{{ Route::has('harga-tbs.index') ? route('harga-tbs.index') : '#' }}"><i class="bi bi-cash-coin"></i> Harga TBS</a>
@endsection

@section('content')
<div class="kartu p-4 mb-4">
    <h4 class="mb-1">Dashboard SawitMentor (Admin)</h4>
    <div class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="kartu p-3"><div class="text-muted">Total Panen</div><h4>{{ number_format($totalPanen, 0, ',', '.') }} kg</h4></div></div>
    <div class="col-md-3"><div class="kartu p-3"><div class="text-muted">Total Pendapatan</div><h4>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h4></div></div>
    <div class="col-md-3"><div class="kartu p-3"><div class="text-muted">Jumlah Petani</div><h4>{{ $jumlahPetani }}</h4></div></div>
    <div class="col-md-3"><div class="kartu p-3"><div class="text-muted">Jumlah Kebun</div><h4>{{ $jumlahKebun }}</h4></div></div>
</div>

<div class="kartu p-4 mb-4">
    <h5>Grafik Panen per Bulan (kg)</h5>
    @if ($grafik->isEmpty())
        <p class="text-muted text-center my-5">Belum ada data panen untuk ditampilkan.</p>
    @else
        <canvas id="grafik" height="90"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('grafik'), {
                type: 'bar',
                data: { labels: @json($grafik->keys()), datasets: [{ label: 'Panen (kg)', data: @json($grafik->values()), backgroundColor: '#7367f0' }] }
            });
        </script>
    @endif
</div>

<div class="kartu p-4">
    <h5>Panen Terbaru</h5>
    <table class="table">
        <thead><tr><th>Tanggal</th><th>Berat (kg)</th><th>Pendapatan</th></tr></thead>
        <tbody>
            @forelse ($terbaru as $p)
                <tr><td>{{ $p['tanggal'] }}</td><td>{{ number_format($p['berat'], 0, ',', '.') }}</td><td>Rp{{ number_format($p['pendapatan'], 0, ',', '.') }}</td></tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted">Belum ada data panen.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection