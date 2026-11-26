@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h4 class="mb-3">Dashboard</h4>

<div class="row g-3 mb-4">
    @foreach ([
        ['Total Panen', number_format($totalPanen, 0, ',', '.') . ' kg', 'bi-basket'],
        ['Total Pendapatan', 'Rp' . number_format($totalPendapatan, 0, ',', '.'), 'bi-cash-stack'],
        ['Jumlah Petani', $jumlahPetani, 'bi-people'],
        ['Jumlah Kebun', $jumlahKebun, 'bi-geo-alt'],
    ] as [$label, $nilai, $ikon])
    <div class="col-6 col-lg-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small"><i class="bi {{ $ikon }}"></i> {{ $label }}</div>
            <div class="fs-5 fw-bold">{{ $nilai }}</div>
        </div></div>
    </div>
    @endforeach
</div>

<div class="card mb-4"><div class="card-body">
    <h6>Panen per Bulan (kg)</h6>
    <canvas id="chartPanen" height="90"></canvas>
</div></div>

<div class="card"><div class="card-body">
    <h6>Panen Terbaru</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th>Tanggal</th><th>Kebun</th><th>Petani</th><th class="text-end">Berat (kg)</th><th class="text-end">Pendapatan</th></tr></thead>
            <tbody>
            @forelse ($panenTerbaru as $p)
                <tr>
                    <td>{{ $p->tanggal_panen->format('d/m/Y') }}</td>
                    <td>{{ $p->kebun->nama_kebun }}</td>
                    <td>{{ $p->kebun->petani->nama }}</td>
                    <td class="text-end">{{ number_format($p->berat_kg, 0, ',', '.') }}</td>
                    <td class="text-end">Rp{{ number_format($p->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada data panen.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('chartPanen'), {
        type: 'bar',
        data: {
            labels: @json($perBulan->pluck('bulan')),
            datasets: [{ label: 'Berat panen (kg)', data: @json($perBulan->pluck('total')), backgroundColor: '#2e6b3e' }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush