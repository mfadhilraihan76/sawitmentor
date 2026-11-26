@extends('layouts.petani')
@section('title', 'Dashboard Petani')

@section('content')
{{-- Sapaan --}}
<div class="hero p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="small opacity-75">{{ now()->translatedFormat('l, d F Y') }}</div>
            <h4 class="mb-1">Selamat datang, {{ $petani->nama }}</h4>
            <div class="opacity-75 small"><i class="bi bi-telephone"></i> {{ $petani->no_hp }} &nbsp; <i class="bi bi-geo-alt"></i> {{ $petani->alamat }}</div>
        </div>
        <div class="text-end">
            <div class="small opacity-75">Harga TBS hari ini</div>
            @if ($hargaSekarang)
                <div class="fs-4 fw-bold">Rp{{ number_format($hargaSekarang->harga_per_kg, 0, ',', '.') }}<span class="fs-6 fw-normal">/kg</span></div>
                <div class="small opacity-75">s/d {{ $hargaSekarang->periode_selesai->format('d/m/Y') }}</div>
            @else
                <div class="fw-semibold">Belum ada harga berlaku</div>
            @endif
        </div>
    </div>
</div>

{{-- Kartu ringkasan --}}
<div class="row g-3 mb-4">
    @foreach ([
        ['Total Panen', number_format($totalPanen, 0, ',', '.') . ' kg', 'bi-basket', '#e8f3ea', '#2e6b3e'],
        ['Total Pendapatan', 'Rp' . number_format($totalPendapatan, 0, ',', '.'), 'bi-cash-stack', '#fdf3dc', '#b8820f'],
        ['Panen Bulan Ini', number_format($panenBulanIni, 0, ',', '.') . ' kg', 'bi-calendar-check', '#e6f0fb', '#2f6db5'],
        ['Jumlah Kebun', $jumlahKebun, 'bi-geo-alt', '#f3e9f8', '#7a3fa0'],
    ] as [$label, $nilai, $ikon, $bg, $warna])
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: {{ $bg }}; color: {{ $warna }}"><i class="bi {{ $ikon }}"></i></div>
            <div>
                <div class="text-muted small">{{ $label }}</div>
                <div class="fw-bold">{{ $nilai }}</div>
            </div>
        </div></div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    {{-- Grafik --}}
    <div class="col-lg-8">
        <div class="card h-100"><div class="card-body">
            <h6 class="mb-3">Grafik Panen per Bulan (kg)</h6>
            @if ($perBulan->isEmpty())
                <div class="text-center text-muted py-5">Belum ada data panen untuk ditampilkan.</div>
            @else
                <canvas id="chartPanen" height="110"></canvas>
            @endif
        </div></div>
    </div>

    {{-- Kebun saya --}}
    <div class="col-lg-4">
        <div class="card h-100"><div class="card-body">
            <h6 class="mb-3">Kebun Saya</h6>
            @forelse ($kebun as $k)
                <div class="border rounded-3 p-3 mb-2">
                    <div class="fw-semibold">{{ $k->nama_kebun }}</div>
                    <div class="small text-muted mb-2"><i class="bi bi-geo-alt"></i> {{ $k->lokasi }} &bull; {{ number_format($k->luas_lahan, 2, ',', '.') }} ha</div>
                    <div class="d-flex justify-content-between small">
                        <span>Total panen</span><strong>{{ number_format($k->panen_sum_berat_kg ?? 0, 0, ',', '.') }} kg</strong>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Pendapatan</span><strong>Rp{{ number_format($k->panen_sum_total_pendapatan ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
            @empty
                <div class="text-muted">Belum ada kebun terdaftar.</div>
            @endforelse
        </div></div>
    </div>
</div>

{{-- Riwayat panen --}}
<div class="card mb-4"><div class="card-body">
    <h6 class="mb-3">Riwayat Panen Terbaru</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Tanggal</th><th>Kebun</th><th class="text-end">Berat (kg)</th><th class="text-end">Harga/kg</th><th class="text-end">Pendapatan</th></tr></thead>
            <tbody>
            @forelse ($riwayat as $p)
                <tr>
                    <td>{{ $p->tanggal_panen->format('d/m/Y') }}</td>
                    <td>{{ $p->kebun->nama_kebun }}</td>
                    <td class="text-end">{{ number_format($p->berat_kg, 0, ',', '.') }}</td>
                    <td class="text-end">Rp{{ number_format($p->hargaTbs->harga_per_kg, 0, ',', '.') }}</td>
                    <td class="text-end fw-semibold">Rp{{ number_format($p->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat panen.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div></div>
@endsection

@push('scripts')
@if ($perBulan->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('chartPanen'), {
        type: 'bar',
        data: {
            labels: @json($perBulan->pluck('bulan')),
            datasets: [{ label: 'Berat panen (kg)', data: @json($perBulan->pluck('total')), backgroundColor: '#2e6b3e', borderRadius: 6 }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>
@endif
@endpush