@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('content')
<div class="row g-6">

  {{-- Header & tombol menu cepat --}}
  <div class="col-12">
    <div class="card">
      <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div>
          <h4 class="mb-1">Dashboard SawitMentor</h4>
          <p class="mb-0 text-body-secondary">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('petani.index') }}" class="btn btn-success">Petani</a>
          <a href="{{ route('kebun.index') }}" class="btn btn-success">Kebun</a>
          <a href="{{ route('panen.index') }}" class="btn btn-success">Panen</a>
          <a href="{{ route('harga-tbs.index') }}" class="btn btn-success">Harga TBS</a>
        </div>
      </div>
    </div>
  </div>

  {{-- Kartu ringkasan --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body">
        <p class="mb-1 text-body-secondary">Total Panen</p>
        <h4 class="mb-0">{{ number_format($totalPanen, 0, ',', '.') }} kg</h4>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body">
        <p class="mb-1 text-body-secondary">Total Pendapatan</p>
        <h4 class="mb-0">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body">
        <p class="mb-1 text-body-secondary">Jumlah Petani</p>
        <h4 class="mb-0">{{ $jumlahPetani }}</h4>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body">
        <p class="mb-1 text-body-secondary">Jumlah Kebun</p>
        <h4 class="mb-0">{{ $jumlahKebun }}</h4>
      </div>
    </div>
  </div>

  {{-- Grafik --}}
  <div class="col-12">
    <div class="card">
      <h5 class="card-header">Grafik Panen per Bulan (kg)</h5>
      <div class="card-body">
        @if ($perBulan->isEmpty())
          <div class="text-center text-body-secondary py-10">Belum ada data panen untuk ditampilkan.</div>
        @else
          <canvas id="chartPanen" height="100"></canvas>
        @endif
      </div>
    </div>
  </div>

  {{-- Panen terbaru --}}
  <div class="col-12">
    <div class="card">
      <h5 class="card-header">Panen Terbaru</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Petani</th>
              <th>Kebun</th>
              <th class="text-end">Berat (kg)</th>
              <th class="text-end">Pendapatan</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($panenTerbaru as $p)
              <tr>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_panen)->format('d/m/Y') }}</td>
                <td>{{ $p->kebun->petani->nama ?? '-' }}</td>
                <td>{{ $p->kebun->nama_kebun ?? '-' }}</td>
                <td class="text-end">{{ number_format($p->berat_kg, 0, ',', '.') }}</td>
                <td class="text-end fw-medium">Rp{{ number_format($p->total_pendapatan, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-body-secondary py-6">Belum ada data panen.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

{{-- Chart.js --}}
@if ($perBulan->isNotEmpty())
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
  <script>
    new Chart(document.getElementById('chartPanen'), {
      type: 'bar',
      data: {
        labels: @json($perBulan->pluck('bulan')),
        datasets: [{
          label: 'Berat panen (kg)',
          data: @json($perBulan->pluck('total')),
          backgroundColor: '#16a34a',
          borderRadius: 6
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>
@endif
@endsection