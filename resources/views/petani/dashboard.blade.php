@extends('layouts.sawit')
@section('title', 'Dashboard Saya')
@section('warna', '#1f8f4e')

@section('menu')
    @include('petani._menu')
@endsection

@section('content')
<div class="hero p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="small opacity-75">{{ now()->translatedFormat('l, d F Y') }}</div>
            <h4 class="mb-1">Selamat datang, {{ $petani->nama ?? $user->name }}</h4>
            @if ($petani)
                <div class="small opacity-75">
                    <i class="bi bi-telephone"></i> {{ $petani->no_hp ?? '-' }} &nbsp;
                    <i class="bi bi-geo-alt"></i> {{ $petani->alamat ?? '-' }}
                </div>
            @else
                <div class="small opacity-75">Data petani untuk akun ini belum terhubung oleh admin.</div>
            @endif
        </div>
        <div class="text-end">
            <div class="small opacity-75">Harga TBS hari ini</div>
            @if ($hargaSekarang)
                <div class="fs-4 fw-bold">Rp{{ number_format($hargaSekarang->harga_per_kg, 0, ',', '.') }}<span class="fs-6 fw-normal">/kg</span></div>
                <div class="small opacity-75">s/d {{ \Carbon\Carbon::parse($hargaSekarang->periode_selesai)->format('d/m/Y') }}</div>
            @else
                <div class="fw-semibold">Belum ada harga berlaku</div>
            @endif
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="kartu p-3"><div class="text-muted">Total Panen</div><h4>{{ number_format($totalPanen, 0, ',', '.') }} kg</h4></div></div>
    <div class="col-md-3"><div class="kartu p-3"><div class="text-muted">Total Pendapatan</div><h4>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h4></div></div>
    <div class="col-md-3"><div class="kartu p-3"><div class="text-muted">Panen Bulan Ini</div><h4>{{ number_format($bulanIni, 0, ',', '.') }} kg</h4></div></div>
    <div class="col-md-3"><div class="kartu p-3"><div class="text-muted">Jumlah Kebun</div><h4>{{ $jumlahKebun }}</h4></div></div>
</div>

<div class="kartu p-4 mb-4">
    <h5>Grafik Panen Saya per Bulan (kg)</h5>
    @if ($grafik->isEmpty())
        <p class="text-muted text-center my-5">Belum ada data panen untuk ditampilkan.</p>
    @else
        <canvas id="grafik" height="90"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('grafik'), {
                type: 'bar',
                data: { labels: @json($grafik->keys()), datasets: [{ label: 'Panen (kg)', data: @json($grafik->values()), backgroundColor: '#1f8f4e' }] }
            });
        </script>
    @endif
</div>

<div class="kartu p-4">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Panen Saya Terbaru</h5>
        <a href="{{ route('petani.kebun') }}" class="btn btn-sm btn-outline-success">Lihat semua</a>
    </div>
    <table class="table mt-3">
        <thead><tr><th>Tanggal</th><th>Berat (kg)</th><th>Harga/kg</th><th>Pendapatan</th></tr></thead>
        <tbody>
            @forelse ($terbaru as $r)
                <tr>
                    <td>{{ $r['tanggal'] ? $r['tanggal']->format('d/m/Y') : '-' }}</td>
                    <td>{{ number_format($r['berat'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($r['harga'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($r['pendapatan'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">Belum ada data panen.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection