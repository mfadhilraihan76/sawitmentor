<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SawitMentor</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Header & Menu -->
        <div class="bg-white p-6 rounded-lg shadow flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard SawitMentor</h1>
                <p class="text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex flex-wrap gap-2 text-sm font-medium">
                <a href="{{ route('petani.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Petani</a>
                <a href="{{ route('kebun.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Kebun</a>
                <a href="{{ route('panen.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Panen</a>
                <a href="{{ route('harga-tbs.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Harga TBS</a>
            </div>
        </div>

        <!-- Kartu Ringkasan -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="text-sm text-gray-500">Total Panen</div>
                <div class="text-xl font-bold text-gray-800">{{ number_format($totalPanen, 0, ',', '.') }} kg</div>
            </div>
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="text-sm text-gray-500">Total Pendapatan</div>
                <div class="text-xl font-bold text-gray-800">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="text-sm text-gray-500">Jumlah Petani</div>
                <div class="text-xl font-bold text-gray-800">{{ $jumlahPetani }}</div>
            </div>
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="text-sm text-gray-500">Jumlah Kebun</div>
                <div class="text-xl font-bold text-gray-800">{{ $jumlahKebun }}</div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="font-semibold text-gray-800 mb-4">Grafik Panen per Bulan (kg)</h2>
            @if ($perBulan->isEmpty())
                <div class="text-center text-gray-500 py-10">Belum ada data panen untuk ditampilkan.</div>
            @else
                <canvas id="chartPanen" height="100"></canvas>
            @endif
        </div>

        <!-- Panen Terbaru -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="font-semibold text-gray-800 mb-4">Panen Terbaru</h2>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b text-gray-700 text-sm">
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Petani</th>
                            <th class="p-3">Kebun</th>
                            <th class="p-3 text-right">Berat (kg)</th>
                            <th class="p-3 text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse ($panenTerbaru as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3">{{ \Carbon\Carbon::parse($p->tanggal_panen)->format('d/m/Y') }}</td>
                                <td class="p-3">{{ $p->kebun->petani->nama ?? '-' }}</td>
                                <td class="p-3">{{ $p->kebun->nama_kebun ?? '-' }}</td>
                                <td class="p-3 text-right">{{ number_format($p->berat_kg, 0, ',', '.') }}</td>
                                <td class="p-3 text-right font-medium">Rp{{ number_format($p->total_pendapatan, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">Belum ada data panen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
</body>
</html>