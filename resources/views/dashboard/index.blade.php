@extends('layouts/contentNavbarLayout')
@section('title', 'Dashboard - SawitMentor')

@section('content')
<style>
    /* 1. Ubah Sidebar Materio menjadi Hijau Tua SawitMentor */
    #layout-menu {
        background-color: #0e3621 !important;
        color: #e2e8f0 !important;
    }
    #layout-menu .app-brand {
        padding-top: 1.2rem;
        padding-bottom: 1.2rem;
    }
    #layout-menu .app-brand-link .app-brand-logo,
    #layout-menu .app-brand-link .app-brand-text {
        display: none !important;
    }
    #layout-menu .app-brand-link::before {
        content: "";
        display: inline-block;
        width: 34px;
        height: 34px;
        background-color: #facc15;
        border-radius: 10px;
        margin-right: 12px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%230e3621' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 22v-9'/%3E%3Cpath d='M12 13c-3-4-7-4-9-2 2 2 5 3 9 2Z'/%3E%3Cpath d='M12 13c3-4 7-4 9-2-2 2-5 3-9 2Z'/%3E%3Cpath d='M12 9c-2-3-5-3-7-1 2 1 4 2 7 1Z'/%3E%3Cpath d='M12 9c2-3 5-3 7-1-2 1-4 2-7 1Z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 20px;
    }
    #layout-menu .app-brand-link::after {
        content: "SawitMentor";
        color: #ffffff;
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: 0.3px;
    }
    #layout-menu .menu-header-text {
        color: #86efac !important;
        opacity: 0.75;
        font-size: 0.72rem;
        letter-spacing: 0.8px;
    }
    #layout-menu .menu-item .menu-link {
        color: #d1fae5 !important;
        border-radius: 10px !important;
        margin: 4px 12px !important;
        padding: 10px 14px !important;
        transition: all 0.2s ease;
    }
    #layout-menu .menu-item .menu-link:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
    }
    #layout-menu .menu-item.active > .menu-link {
        background: linear-gradient(90deg, #4ade80 0%, #22c55e 100%) !important;
        color: #062815 !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(74, 222, 128, 0.3) !important;
    }
    #layout-menu .menu-item.active > .menu-link i {
        color: #062815 !important;
    }

    /* 2. Rapikan Navbar Atas & Profil Admin */
    #layout-navbar .github-button,
    #layout-navbar img[alt="alt"],
    #layout-navbar .navbar-nav .nav-item.dropdown-user img {
        display: none !important;
    }
    #layout-navbar .navbar-nav.ms-auto::after {
        content: "Admin";
        display: inline-flex;
        align-items: center;
        font-weight: 700;
        color: #1f2937;
        background: #fef9c3;
        border: 1.5px solid #facc15;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.85rem;
        margin-left: 10px;
    }

    /* 3. Styling Konten Dashboard SawitMentor */
    .sawit-banner {
        background: linear-gradient(135deg, #15803d 0%, #22c55e 100%);
        border-radius: 18px;
        padding: 26px 30px;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(22, 163, 74, 0.22);
    }
    .sawit-banner .btn-quick {
        background-color: #ffffff;
        color: #15803d;
        font-weight: 600;
        border-radius: 10px;
        padding: 8px 18px;
        border: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        transition: all 0.2s;
    }
    .sawit-banner .btn-quick:hover {
        background-color: #f0fdf4;
        transform: translateY(-2px);
    }
    .sawit-banner .btn-tbs {
        background-color: #facc15;
        color: #1f2937;
        font-weight: 700;
        border-radius: 10px;
        padding: 8px 18px;
        border: none;
        box-shadow: 0 2px 8px rgba(250, 204, 21, 0.35);
        transition: all 0.2s;
    }
    .sawit-banner .btn-tbs:hover {
        background-color: #eab308;
        transform: translateY(-2px);
    }
    .stat-card {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .content-card {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    }
    .tbs-card {
        background: linear-gradient(145deg, #fde047 0%, #facc15 100%);
        border: none;
        border-radius: 16px;
        color: #1f2937;
        box-shadow: 0 8px 20px rgba(250, 204, 21, 0.28);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        margin-right: 10px;
    }
    .badge-kebun {
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.78rem;
    }
</style>

{{-- Banner Hijau --}}
<div class="sawit-banner mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h4 class="fw-bold text-white mb-1">Selamat datang di SawitMentor</h4>
        <div class="opacity-90 small">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ url('/petani') }}" class="btn btn-quick">Petani</a>
        <a href="{{ url('/kebun') }}" class="btn btn-quick">Kebun</a>
        <a href="{{ url('/panen') }}" class="btn btn-quick">Panen</a>
        <a href="{{ url('/harga-tbs') }}" class="btn btn-tbs">Harga TBS</a>
    </div>
</div>

{{-- 4 Kartu Statistik dengan Ikon SVG Tajam --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background-color: #dcfce7; color: #16a34a;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <div>
                    <div class="text-muted small mb-1">Total Panen</div>
                    <div class="fs-5 fw-bold mb-0" style="color: #15803d;">{{ number_format($totalPanen ?? 0, 0, ',', '.') }} kg</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background-color: #fef3c7; color: #d97706;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                </div>
                <div>
                    <div class="text-muted small mb-1">Total Pendapatan</div>
                    <div class="fs-5 fw-bold mb-0" style="color: #92400e;">Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background-color: #dbeafe; color: #2563eb;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <div class="text-muted small mb-1">Jumlah Petani</div>
                    <div class="fs-5 fw-bold mb-0" style="color: #1d4ed8;">{{ $jumlahPetani ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background-color: #f3e8ff; color: #9333ea;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z"/><path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2z"/></svg>
                </div>
                <div>
                    <div class="text-muted small mb-1">Jumlah Kebun</div>
                    <div class="fs-5 fw-bold mb-0" style="color: #7e22ce;">{{ $jumlahKebun ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik & Kartu Kuning Harga TBS --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card content-card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Grafik Panen per Bulan (kg)</h6>
                    <span class="badge rounded-pill px-3 py-2" style="background-color: #dcfce7; color: #16a34a;">{{ date('Y') }}</span>
                </div>
                <div style="height: 240px;">
                    <canvas id="chartPanen"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card tbs-card p-4">
            <div>
                <div class="fw-semibold mb-4">Harga TBS Hari Ini</div>
                <div class="display-6 fw-bold mb-1" style="color: #1f2937;">
                    Rp{{ number_format($hargaSekarang->harga_per_kg ?? ($hargaTbsHariIni ?? 2450), 0, ',', '.') }}
                </div>
                <div class="small fw-medium opacity-75">per kilogram</div>
            </div>
            <div class="small opacity-75 mt-4">
                Data harga terbaru dari menu Harga TBS.
            </div>
        </div>
    </div>
</div>

{{-- Tabel Panen Terbaru --}}
<div class="card content-card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Panen Terbaru</h6>
            <a href="{{ url('/panen') }}" class="small fw-semibold text-decoration-underline" style="color: #16a34a;">Lihat semua</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="text-muted small" style="background-color: #f9fafb;">
                    <tr>
                        <th class="border-0 py-3">TANGGAL</th>
                        <th class="border-0 py-3">PETANI</th>
                        <th class="border-0 py-3">KEBUN</th>
                        <th class="border-0 py-3 text-end">BERAT (KG)</th>
                        <th class="border-0 py-3 text-end">PENDAPATAN</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $badgeStyles = [
                        ['bg' => '#f3e8ff', 'text' => '#7e22ce'],
                        ['bg' => '#ffedd5', 'text' => '#c2410c'],
                        ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                        ['bg' => '#dcfce7', 'text' => '#15803d'],
                    ];
                @endphp
                @forelse ($panenTerbaru ?? [] as $index => $p)
                    @php
                        $namaPetani = $p->kebun->petani->nama ?? 'Petani';
                        $words = explode(' ', trim($namaPetani));
                        $inisial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        $style = $badgeStyles[$index % count($badgeStyles)];
                    @endphp
                    <tr>
                        <td class="py-3 text-muted">{{ \Carbon\Carbon::parse($p->tanggal_panen)->format('d M Y') }}</td>
                        <td class="py-3">
                            <span class="avatar-circle" style="background-color: {{ $style['bg'] }}; color: {{ $style['text'] }};">
                                {{ $inisial }}
                            </span>
                            <span class="fw-medium">{{ $namaPetani }}</span>
                        </td>
                        <td class="py-3">
                            <span class="badge-kebun" style="background-color: {{ $style['bg'] }}; color: {{ $style['text'] }};">
                                {{ $p->kebun->nama_kebun ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 text-end fw-semibold">{{ number_format($p->berat_kg, 0, ',', '.') }}</td>
                        <td class="py-3 text-end fw-bold" style="color: #16a34a;">Rp{{ number_format($p->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data panen.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const rawLabels = @json(($perBulan ?? collect())->pluck('bulan'));
    const rawData = @json(($perBulan ?? collect())->pluck('total'));

    const labelsBulan = rawLabels.length > 0 ? rawLabels : ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dataBulan = rawData.length > 0 ? rawData : [0,0,0,0,0,0,0,0,0,0,0,0];

    const maxVal = Math.max(...dataBulan, 0);
    const barColors = dataBulan.map(val => (val === maxVal && val > 0) ? '#facc15' : '#34d378');

    new Chart(document.getElementById('chartPanen'), {
        type: 'bar',
        data: {
            labels: labelsBulan,
            datasets: [{
                label: 'Berat panen (kg)',
                data: dataBulan,
                backgroundColor: barColors,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, border: { display: false }, grid: { color: '#f3f4f6' } }
            }
        }
    });
</script>
@endsection
