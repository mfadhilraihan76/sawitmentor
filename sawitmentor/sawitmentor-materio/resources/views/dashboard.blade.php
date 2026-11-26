@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
<script type="module">
  const labels = @json($chartLabels ?? []);
  const data = @json($chartData ?? []);
  const el = document.querySelector('#grafikPanen');

  if (el && data.length) {
    new ApexCharts(el, {
      chart: { type: 'bar', height: 300, toolbar: { show: false } },
      series: [{ name: 'Panen (kg)', data: data }],
      xaxis: { categories: labels },
      colors: [window.config.colors.success],
      plotOptions: { bar: { borderRadius: 6, columnWidth: '40%' } },
      dataLabels: { enabled: false },
      yaxis: { labels: { formatter: v => v.toLocaleString('id-ID') } }
    }).render();
  }
</script>
@endsection

@section('content')
<div class="row gy-6">

    <!-- Header -->
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="mb-1">Dashboard SawitMentor</h4>
                    <p class="mb-0 text-body-secondary">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ url('/petani') }}" class="btn btn-success">Petani</a>
                    <a href="{{ url('/kebun') }}" class="btn btn-success">Kebun</a>
                    <a href="{{ url('/panen') }}" class="btn btn-success">Panen</a>
                    <a href="{{ url('/harga-tbs') }}" class="btn btn-success">Harga TBS</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar"><span class="avatar-initial rounded bg-label-success"><i class="icon-base ri ri-leaf-line icon-24px"></i></span></div>
                    <div>
                        <p class="mb-0 text-body-secondary">Total Panen</p>
                        <h4 class="mb-0">{{ number_format($totalPanen ?? 0, 0, ',', '.') }} kg</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar"><span class="avatar-initial rounded bg-label-warning"><i class="icon-base ri ri-money-dollar-circle-line icon-24px"></i></span></div>
                    <div>
                        <p class="mb-0 text-body-secondary">Total Pendapatan</p>
                        <h4 class="mb-0">Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar"><span class="avatar-initial rounded bg-label-info"><i class="icon-base ri ri-user-3-line icon-24px"></i></span></div>
                    <div>
                        <p class="mb-0 text-body-secondary">Jumlah Petani</p>
                        <h4 class="mb-0">{{ $jumlahPetani ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar"><span class="avatar-initial rounded bg-label-primary"><i class="icon-base ri ri-plant-line icon-24px"></i></span></div>
                    <div>
                        <p class="mb-0 text-body-secondary">Jumlah Kebun</p>
                        <h4 class="mb-0">{{ $jumlahKebun ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Grafik Panen per Bulan (kg)</h5>
            </div>
            <div class="card-body">
                @if (!empty($chartData))
                    <div id="grafikPanen"></div>
                @else
                    <p class="text-center text-body-secondary my-5">Belum ada data panen untuk ditampilkan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Panen Terbaru -->
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Panen Terbaru</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
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
                        @forelse (($panenTerbaru ?? []) as $panen)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($panen->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $panen->petani->nama ?? '-' }}</td>
                            <td>{{ $panen->kebun->nama ?? '-' }}</td>
                            <td class="text-end">{{ number_format($panen->berat, 0, ',', '.') }}</td>
                            <td class="text-end">Rp{{ number_format($panen->pendapatan ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-body-secondary py-5">Belum ada data panen.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
