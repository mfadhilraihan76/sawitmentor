@extends('layouts.sawit_top')
@section('title', 'Dashboard Saya')
@section('warna', '#1f8f4e')

@section('content')
{{-- Banner: sapaan dinamis + jam realtime --}}
<div class="hero p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="small opacity-75" id="tanggal">{{ now()->translatedFormat('l, d F Y') }}</div>
            <h4 class="mb-1"><span id="sapaan" data-nama="{{ $petani->nama ?? $user->name }}">Selamat datang, {{ $petani->nama ?? $user->name }}!</span></h4>
            @if ($petani)
                <div class="small opacity-75">Pantau perkembangan kebun dan hasil panen Anda.</div>
            @else
                <div class="small opacity-75">Data petani untuk akun ini belum terhubung oleh admin.</div>
            @endif
        </div>
        <div class="text-end">
            <div class="jam" id="jam">--:--:--</div>
            <div class="small opacity-75"><span class="titik me-1"></span><span id="zona">Waktu setempat</span></div>
        </div>
    </div>
</div>

{{-- Statistik kebun --}}
<h6 class="mb-3">Statistik Kebun</h6>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="kartu p-3 h-100">
        <div class="ikon ikon-hijau"><i class="bi bi-basket"></i></div>
        <h4 class="mb-0">{{ number_format($totalPanen, 0, ',', '.') }} kg</h4>
        <div class="text-muted small">Total Panen</div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="kartu p-3 h-100">
        <div class="ikon ikon-emas"><i class="bi bi-wallet2"></i></div>
        <h4 class="mb-0">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
        <div class="text-muted small">Pendapatan</div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="kartu p-3 h-100">
        <div class="ikon ikon-biru"><i class="bi bi-flower1"></i></div>
        <h4 class="mb-0">{{ $jumlahKebun }}</h4>
        <div class="text-muted small">Jumlah Kebun</div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="kartu p-3 h-100">
        <div class="ikon ikon-ungu"><i class="bi bi-graph-up-arrow"></i></div>
        <h4 class="mb-0 {{ ($totalPendapatan - $totalBiaya) < 0 ? 'text-danger' : '' }}">Rp{{ number_format($totalPendapatan - $totalBiaya, 0, ',', '.') }}</h4>
        <div class="text-muted small">Keuntungan Bersih</div>
    </div></div>
</div>

{{-- Informasi hari ini --}}
<h6 class="mb-3">Informasi Hari Ini</h6>
<div class="row g-3 mb-4">
    <div class="col-lg-7"><div class="kartu p-3 h-100" id="kartuCuaca">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-semibold"><i class="bi bi-geo-alt text-success"></i> Cuaca <span class="text-muted fw-normal">- <span id="cuacaLokasi">Pekanbaru</span></span></div>
            <button type="button" class="btn btn-sm btn-outline-success" id="btnLokasi"><i class="bi bi-crosshair"></i> Lokasi saya</button>
        </div>
        <div id="cuacaMuat">
            <div class="skel mb-2" style="height:56px;width:55%"></div>
            <div class="skel mb-2" style="height:18px;width:80%"></div>
            <div class="skel" style="height:64px"></div>
        </div>
        <div id="cuacaIsi" style="display:none">
            <div class="d-flex align-items-center gap-3 mb-2">
                <i class="bi bi-cloud-sun cuaca-ikon" id="cuacaIkon"></i>
                <div>
                    <div class="d-flex align-items-start"><span class="fs-1 fw-bold lh-1" id="cuacaSuhu">--</span><span class="fs-5 text-muted">&deg;C</span></div>
                    <div class="text-muted" id="cuacaKondisi">-</div>
                </div>
                <div class="ms-auto small text-muted text-end">
                    <div><i class="bi bi-thermometer-half"></i> Terasa <span id="cuacaTerasa">--</span>&deg;C</div>
                    <div><i class="bi bi-droplet"></i> Lembap <span id="cuacaLembap">--</span>%</div>
                    <div><i class="bi bi-wind"></i> Angin <span id="cuacaAngin">--</span> km/jam</div>
                    <div><i class="bi bi-cloud-rain"></i> Peluang hujan <span id="cuacaHujan">--</span>%</div>
                </div>
            </div>
            <div class="alert alert-success py-2 small mb-3" id="cuacaSaran"></div>
            <div class="row g-2" id="cuacaRamalan"></div>
            <div class="small text-muted mt-2">Diperbarui <span id="cuacaUpdate">-</span> (otomatis tiap 10 menit)</div>
        </div>
        <div id="cuacaGalat" style="display:none" class="text-center text-muted py-3">
            <div class="mb-2">Data cuaca tidak dapat dimuat. Periksa koneksi internet.</div>
            <button type="button" class="btn btn-sm btn-success" id="btnUlang">Coba lagi</button>
        </div>
    </div></div>

    <div class="col-lg-5"><div class="kartu p-3 h-100" id="hargaLive">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-semibold"><i class="bi bi-graph-up text-primary"></i> Harga TBS</div>
            <span class="badge bg-light text-success border"><span class="titik me-1"></span>LIVE</span>
        </div>
        <h3 class="mb-0" style="color:#2563eb" id="dhHarga">Rp --</h3>
        <div class="text-muted small" id="dhRentang">Memuat data harga...</div>
        <div class="small mt-2" id="dhUbah"></div>
        <div class="small text-muted mt-2">Diperbarui <span id="dhWaktu">-</span></div>
        <a href="{{ url('/petani/harga-tbs') }}" class="btn btn-sm btn-outline-success mt-2">Lihat perkembangan harga</a>
        @push('scripts')
        <script src="{{ asset('js/harga-live.js') }}"></script>
        <script>
        (function () {
            var lama = null;
            function rp(n) { return 'Rp' + Math.round(n).toLocaleString('id-ID'); }
            HargaLive.mulai('{{ route('harga-tbs.index') }}', function (d) {
                if (!d) { document.getElementById('dhWaktu').textContent = 'gagal memuat'; return; }
                document.getElementById('dhWaktu').textContent = d.waktu;
                if (!d.sekarang) { document.getElementById('dhHarga').textContent = 'Rp --'; document.getElementById('dhRentang').textContent = 'Menunggu data harga'; return; }
                document.getElementById('dhHarga').innerHTML = rp(d.sekarang.harga) + '<span class="fs-6 fw-normal">/kg</span>';
                document.getElementById('dhRentang').textContent = 'Berlaku s/d ' + d.sekarang.selesai;
                var u = '';
                if (d.sebelumnya) {
                    u = 'Sebelumnya ' + rp(d.sebelumnya.harga) + ' ';
                    if (d.perubahan > 0) u += '<span class="badge bg-success">&#9650; ' + rp(d.perubahan) + '</span>';
                    else if (d.perubahan < 0) u += '<span class="badge bg-danger">&#9660; ' + rp(Math.abs(d.perubahan)) + '</span>';
                    else u += '<span class="badge bg-secondary">Tetap</span>';
                }
                document.getElementById('dhUbah').innerHTML = u;
                if (lama !== null && lama !== d.sekarang.harga) {
                    var k = document.getElementById('hargaLive'); k.classList.remove('flash'); void k.offsetWidth; k.classList.add('flash');
                    window.toast('Harga TBS berubah: ' + rp(d.sekarang.harga) + '/kg', d.sekarang.harga > lama ? '#16a34a' : '#dc2626');
                }
                lama = d.sekarang.harga;
            }, 30);
        })();
        </script>
        @endpush
    </div></div>
</div>

{{-- Grafik produksi --}}
<div class="kartu p-4 mb-4">
    <h6>Grafik Produksi Panen (kg)</h6>
    @if ($grafik->isEmpty())
        <p class="text-muted text-center my-5">Grafik panen bulanan akan ditampilkan di sini.</p>
    @else
        <canvas id="grafik" height="90"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('grafik'), {
                type: 'bar',
                data: { labels: @json($grafik->keys()), datasets: [{ label: 'Panen (kg)', data: @json($grafik->values()), backgroundColor: '#1f8f4e', borderRadius: 8 }] },
                options: { animation: { duration: 1400, easing: 'easeOutQuart' }, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        </script>
    @endif
</div>

{{-- Aktivitas kebun --}}
<h6 class="mb-3">Aktivitas Kebun</h6>
<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="kartu p-3 h-100">
        <div class="fw-semibold mb-2"><i class="bi bi-calendar-check text-success"></i> Jadwal perawatan</div>
        @forelse ($jadwalTerdekat as $j)
            <div class="small mb-1">
                <span class="badge {{ $j->tanggal->lt(today()) ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $j->tanggal->format('d/m') }}</span>
                {{ $j->judul }}
            </div>
        @empty
            <div class="text-muted small">Belum ada jadwal perawatan kebun.</div>
        @endforelse
        <a class="small" href="{{ route('petani.jadwal') }}">Kelola jadwal</a>
    </div></div>
    <div class="col-md-6"><div class="kartu p-3 h-100">
        <div class="fw-semibold mb-2"><i class="bi bi-book text-success"></i> Tips hari ini</div>
        <div class="small"><strong>{{ $tipsHariIni['judul'] }}</strong><br>{{ $tipsHariIni['ringkas'] }} <a href="{{ route('petani.edukasi') }}">Selengkapnya</a></div>
    </div></div>
</div>

{{-- Panen terbaru --}}
<div class="kartu p-4">
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Panen Saya Terbaru</h6>
        <a href="{{ route('petani.kebun') }}" class="btn btn-sm btn-outline-success">Lihat semua</a>
    </div>
    <div class="table-responsive">
        <table class="table mt-3 mb-0">
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
</div>
@endsection

@push('scripts')
<script>
(function () {
    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    // ===== Jam dan sapaan realtime =====
    var zona = '';
    try {
        var bagian = new Intl.DateTimeFormat('id-ID', { timeZoneName: 'short' }).formatToParts(new Date());
        bagian.forEach(function (p) { if (p.type === 'timeZoneName') zona = p.value; });
    } catch (e) {}
    function tik() {
        var d = new Date();
        document.getElementById('jam').textContent = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
        document.getElementById('tanggal').textContent = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        document.getElementById('zona').textContent = zona ? ('Waktu setempat (' + zona + ')') : 'Waktu setempat';
        var h = d.getHours();
        var s = h < 11 ? 'Selamat pagi' : (h < 15 ? 'Selamat siang' : (h < 18 ? 'Selamat sore' : 'Selamat malam'));
        var el = document.getElementById('sapaan');
        el.textContent = s + ', ' + el.getAttribute('data-nama') + '!';
    }
    tik();
    setInterval(tik, 1000);

    // ===== Cuaca realtime (Open-Meteo, tanpa API key) =====
    var lokasi = { lat: 0.5071, lon: 101.4478, nama: 'Pekanbaru' };
    var KODE = {
        0: ['Cerah', 'bi-sun'], 1: ['Cerah berawan', 'bi-cloud-sun'], 2: ['Berawan sebagian', 'bi-cloud-sun'], 3: ['Berawan', 'bi-clouds'],
        45: ['Berkabut', 'bi-cloud-fog'], 48: ['Berkabut', 'bi-cloud-fog']
    };
    function info(c) {
        if (KODE[c]) return KODE[c];
        if (c >= 51 && c <= 57) return ['Gerimis', 'bi-cloud-drizzle'];
        if (c >= 61 && c <= 67) return [c >= 65 ? 'Hujan lebat' : 'Hujan', c >= 65 ? 'bi-cloud-rain-heavy' : 'bi-cloud-rain'];
        if (c >= 71 && c <= 77) return ['Salju', 'bi-snow'];
        if (c >= 80 && c <= 82) return [c === 82 ? 'Hujan lebat' : 'Hujan lokal', c === 82 ? 'bi-cloud-rain-heavy' : 'bi-cloud-rain'];
        if (c >= 95) return ['Badai petir', 'bi-cloud-lightning-rain'];
        return ['Berawan', 'bi-cloud'];
    }
    function saran(kode, hujan, suhu) {
        if (kode >= 95) return 'Ada potensi petir, hindari bekerja di area kebun yang terbuka.';
        if (hujan >= 70) return 'Peluang hujan tinggi, tunda pemupukan dan atur waktu panen.';
        if (hujan >= 40) return 'Hujan bisa turun sewaktu-waktu, siapkan perlengkapan dan terpal untuk TBS.';
        if (suhu >= 34) return 'Cuaca panas, pastikan pekerja cukup minum dan beristirahat.';
        return 'Cuaca mendukung untuk beraktivitas di kebun.';
    }
    function tampil(id) { ['cuacaMuat', 'cuacaIsi', 'cuacaGalat'].forEach(function (x) { document.getElementById(x).style.display = (x === id) ? '' : 'none'; }); }
    function set(id, v) { document.getElementById(id).textContent = v; }

    function muat() {
        tampil('cuacaMuat');
        set('cuacaLokasi', lokasi.nama);
        var url = 'https://api.open-meteo.com/v1/forecast?latitude=' + lokasi.lat + '&longitude=' + lokasi.lon +
            '&current=temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m' +
            '&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max' +
            '&timezone=auto&forecast_days=5';
        fetch(url).then(function (r) { if (!r.ok) throw new Error('http'); return r.json(); }).then(function (j) {
            var c = j.current, dly = j.daily, i = info(c.weather_code);
            document.getElementById('cuacaIkon').className = 'bi ' + i[1] + ' cuaca-ikon';
            set('cuacaSuhu', Math.round(c.temperature_2m));
            set('cuacaKondisi', i[0]);
            set('cuacaTerasa', Math.round(c.apparent_temperature));
            set('cuacaLembap', Math.round(c.relative_humidity_2m));
            set('cuacaAngin', Math.round(c.wind_speed_10m));
            var hujan = Math.round(dly.precipitation_probability_max[0] || 0);
            set('cuacaHujan', hujan);
            set('cuacaSaran', saran(c.weather_code, hujan, c.temperature_2m));
            var html = '';
            for (var k = 0; k < dly.time.length; k++) {
                var di = info(dly.weather_code[k]);
                var nama = k === 0 ? 'Hari ini' : new Date(dly.time[k] + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'short' });
                html += '<div class="col"><div class="hari"><div class="small fw-semibold">' + nama + '</div>' +
                    '<i class="bi ' + di[1] + ' fs-4 text-success"></i>' +
                    '<div class="small">' + Math.round(dly.temperature_2m_max[k]) + '&deg; <span class="text-muted">' + Math.round(dly.temperature_2m_min[k]) + '&deg;</span></div>' +
                    '<div class="small text-primary"><i class="bi bi-droplet"></i> ' + Math.round(dly.precipitation_probability_max[k] || 0) + '%</div></div></div>';
            }
            document.getElementById('cuacaRamalan').innerHTML = html;
            var n = new Date();
            set('cuacaUpdate', pad(n.getHours()) + ':' + pad(n.getMinutes()) + ':' + pad(n.getSeconds()));
            tampil('cuacaIsi');
        }).catch(function () { tampil('cuacaGalat'); });
    }

    document.getElementById('btnUlang').addEventListener('click', muat);
    document.getElementById('btnLokasi').addEventListener('click', function () {
        if (!navigator.geolocation) { alert('Peramban Anda tidak mendukung deteksi lokasi.'); return; }
        navigator.geolocation.getCurrentPosition(function (p) {
            lokasi = { lat: p.coords.latitude, lon: p.coords.longitude, nama: 'Lokasi Anda' };
            muat();
        }, function () { alert('Izin lokasi ditolak, cuaca tetap memakai Pekanbaru.'); }, { timeout: 8000 });
    });

    muat();
    setInterval(muat, 10 * 60 * 1000);
})();
</script>
@endpush
