<!doctype html>
<html lang="id">
@php
$rn = optional(request()->route())->getName() ?? '';
$tema = [
    'petani.kebun' => ['#0d9488', '#34d399'],
    'petani.panen' => ['#f59e0b', '#f97316'],
    'petani.keuangan' => ['#ea580c', '#facc15'],
    'petani.harga' => ['#2563eb', '#22d3ee'],
    'petani.jadwal' => ['#7c3aed', '#e879f9'],
    'petani.perawatan' => ['#7c3aed', '#e879f9'],
    'petani.edukasi' => ['#db2777', '#fb923c'],
    'petani.notifikasi' => ['#dc2626', '#fb7185'],
    'petani.profil' => ['#4f46e5', '#38bdf8'],
    'petani.laporan' => ['#0891b2', '#2dd4bf'],
];
$c = ['#16a34a', '#84cc16'];
foreach ($tema as $awalan => $w) { if (\Illuminate\Support\Str::startsWith($rn, $awalan)) { $c = $w; break; } }
$fotoAkun = auth()->user()->foto ?? null;
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SawitMentor')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --warna: {{ $c[0] }}; --warna2: {{ $c[1] }}; }
        body { background: radial-gradient(circle at 8% 0%, #dff7e8 0, transparent 38%), radial-gradient(circle at 92% 8%, #fff0c9 0, transparent 34%), radial-gradient(circle at 50% 100%, #e6ecff 0, transparent 40%), #f6f7fb; background-attachment: fixed; }
        .topbar { background:rgba(255,255,255,.92); backdrop-filter:blur(8px); box-shadow:0 2px 12px rgba(0,0,0,.08); z-index:1030; border-bottom:3px solid transparent; border-image:linear-gradient(90deg,#22c55e,#facc15,#f97316,#ec4899,#8b5cf6,#3b82f6) 1; }
        .topbar .logo { height:38px; width:auto; }
        .topbar .nav-link { color:#444; border-radius:10px; padding:.45rem .8rem; transition:background .2s, color .2s, transform .2s; }
        .topbar .nav-link:hover { transform:translateY(-1px); background:#f0f3f8; }
        .topbar .nav-link.active, .topbar .nav-link.show { background:linear-gradient(135deg,var(--warna),var(--warna2)); color:#fff; box-shadow:0 4px 10px rgba(0,0,0,.15); }
        .topbar .dropdown-menu { border:0; box-shadow:0 10px 26px rgba(0,0,0,.15); border-radius:12px; padding:.4rem; }
        .topbar .dropdown-menu.show { animation:naik .22s ease both; }
        .topbar .dropdown-item { border-radius:8px; padding:.45rem .7rem; }
        .topbar .dropdown-item:hover { background:#f0f3f8; }
        .topbar .dropdown-item.active, .topbar .dropdown-item:active { background:linear-gradient(135deg,var(--warna),var(--warna2)); color:#fff; }
        .ic { display:inline-flex; width:26px; height:26px; border-radius:8px; align-items:center; justify-content:center; margin-right:.4rem; color:#fff; font-size:.85rem; }
        .ic-hijau { background:#22a45d; } .ic-oranye { background:#f59e0b; } .ic-biru { background:#3b82f6; } .ic-ungu { background:#8b5cf6; }
        .ic-pink { background:#ec4899; } .ic-merah { background:#ef4444; } .ic-cyan { background:#06b6d4; } .ic-teal { background:#14b8a6; } .ic-indigo { background:#6366f1; }
        .avatar { width:34px; height:34px; border-radius:50%; object-fit:cover; display:inline-flex; align-items:center; justify-content:center; background:linear-gradient(135deg,var(--warna),var(--warna2)); color:#fff; font-weight:600; }
        .avatar-lg { width:120px; height:120px; font-size:2.5rem; }

        .hero { background:linear-gradient(120deg, var(--warna), var(--warna2), var(--warna)); background-size:200% 200%; animation:geser 12s ease infinite, naik .6s ease both; color:#fff; border-radius:16px; box-shadow:0 8px 22px rgba(0,0,0,.12); }
        .hero .jam { font-size:2.2rem; font-weight:700; letter-spacing:1px; font-variant-numeric:tabular-nums; line-height:1.1; }
        main > .kartu.p-4.mb-4:first-child:not(:has(form)) { background:linear-gradient(120deg,var(--warna),var(--warna2)); color:#fff; position:relative; overflow:hidden; box-shadow:0 8px 22px rgba(0,0,0,.12); }
        main > .kartu.p-4.mb-4:first-child:not(:has(form))::after { content:''; position:absolute; right:-30px; top:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.18); }
        main > .kartu.p-4.mb-4:first-child:not(:has(form)) .text-muted { color:rgba(255,255,255,.88) !important; }

        .kartu { background:#fff; border-radius:16px; box-shadow:0 3px 10px rgba(0,0,0,.06); transition:transform .25s ease, box-shadow .25s ease; animation:naik .55s ease both; }
        .kartu:hover { transform:translateY(-3px); box-shadow:0 12px 26px rgba(0,0,0,.11); }
        .row.g-3 > [class*="col"]:nth-child(4n+1) > .kartu.p-3 { border-top:4px solid #22c55e; }
        .row.g-3 > [class*="col"]:nth-child(4n+2) > .kartu.p-3 { border-top:4px solid #f59e0b; }
        .row.g-3 > [class*="col"]:nth-child(4n+3) > .kartu.p-3 { border-top:4px solid #3b82f6; }
        .row.g-3 > [class*="col"]:nth-child(4n+4) > .kartu.p-3 { border-top:4px solid #a855f7; }
        .ikon { width:42px; height:42px; border-radius:13px; display:inline-flex; align-items:center; justify-content:center; font-size:1.25rem; margin-bottom:.4rem; }
        .ikon-hijau { background:#dcfce7; color:#16a34a; } .ikon-emas { background:#fef3c7; color:#d97706; }
        .ikon-biru { background:#dbeafe; color:#2563eb; } .ikon-ungu { background:#f3e8ff; color:#9333ea; }

        .table thead th { background:color-mix(in srgb, var(--warna) 13%, white); border-bottom:0; }
        .table tbody tr { transition:background .15s; }
        .table tbody tr:hover { background:color-mix(in srgb, var(--warna) 7%, white); }
        .btn { transition:transform .15s, box-shadow .15s; border-radius:10px; }
        .btn:hover { transform:translateY(-1px); box-shadow:0 5px 12px rgba(0,0,0,.14); }
        .btn-success { background:linear-gradient(135deg,var(--warna),var(--warna2)); border:0; color:#fff; }
        .btn-success:hover, .btn-success:focus { background:linear-gradient(135deg,var(--warna2),var(--warna)); color:#fff; }
        .btn-outline-success { color:var(--warna); border-color:var(--warna); }
        .btn-outline-success:hover { background:var(--warna); border-color:var(--warna); color:#fff; }
        .form-control:focus, .form-select:focus { border-color:var(--warna); box-shadow:0 0 0 .2rem color-mix(in srgb, var(--warna) 25%, transparent); }
        a { color:var(--warna); }
        .alert { animation:naik .4s ease both; transition:opacity .5s; }

        .skel { background:linear-gradient(90deg,#eee 25%,#f7f7f7 37%,#eee 63%); background-size:400% 100%; animation:kilau 1.3s ease infinite; border-radius:8px; }
        .cuaca-ikon { font-size:3.2rem; color:#f59e0b; animation:ambang 3s ease-in-out infinite; }
        .hari { background:linear-gradient(180deg,#f0fdf4,#fffbeb); border-radius:12px; padding:.5rem .25rem; text-align:center; transition:transform .2s; }
        .hari:hover { transform:translateY(-3px); }
        .titik { display:inline-block; width:8px; height:8px; border-radius:50%; background:#2fb26a; animation:denyut 1.6s infinite; }
        .flash { animation:kedip 1.2s ease; }
        .modul { cursor:default; }
        #tampungToast { position:fixed; right:16px; bottom:16px; z-index:2000; display:flex; flex-direction:column; gap:8px; }
        .toast-sm { color:#fff; padding:.7rem 1rem; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,.2); animation:naik .35s ease both; max-width:320px; }

        @keyframes naik { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
        @keyframes geser { 0% { background-position:0% 50%; } 50% { background-position:100% 50%; } 100% { background-position:0% 50%; } }
        @keyframes kilau { 0% { background-position:100% 50%; } 100% { background-position:0 50%; } }
        @keyframes ambang { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-5px); } }
        @keyframes denyut { 0% { box-shadow:0 0 0 0 rgba(47,178,106,.6); } 70% { box-shadow:0 0 0 8px rgba(47,178,106,0); } 100% { box-shadow:0 0 0 0 rgba(47,178,106,0); } }
        @keyframes kedip { 0% { background:#fde68a; } 100% { background:transparent; } }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation:none !important; transition:none !important; } }
        @media print {
            .topbar, .no-print, #tampungToast { display:none !important; }
            body { background:#fff; }
            .kartu { box-shadow:none; animation:none; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg topbar sticky-top no-print">
    <div class="container-xxl">
        <a class="navbar-brand me-4" href="{{ route('petani.dashboard') }}">
            <img class="logo" src="{{ asset('images/logo.png') }}" alt="SawitMentor">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuAtas" aria-controls="menuAtas" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuAtas">
            @include('petani._menu')
            <div class="dropdown mt-3 mt-lg-0">
                <a class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    @if ($fotoAkun)
                        <img class="avatar" src="{{ asset($fotoAkun) }}" alt="Foto profil">
                    @else
                        <span class="avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                    <span>{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('petani.profil') }}"><span class="ic ic-indigo"><i class="bi bi-person-circle"></i></span>Profil saya</a></li>
                    <li><a class="dropdown-item" href="{{ route('petani.profil') }}#password"><span class="ic ic-oranye"><i class="bi bi-key-fill"></i></span>Ubah password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger"><span class="ic ic-merah"><i class="bi bi-box-arrow-right"></i></span>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<main class="container-xxl py-4">
    @yield('content')
</main>
<div id="tampungToast"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.toast = function (pesan, warna) {
    var t = document.createElement('div');
    t.className = 'toast-sm';
    t.style.background = warna || '#16a34a';
    t.textContent = pesan;
    document.getElementById('tampungToast').appendChild(t);
    setTimeout(function () { t.style.opacity = 0; t.style.transition = 'opacity .4s'; setTimeout(function () { t.remove(); }, 450); }, 5000);
};
(function () {
    document.querySelectorAll('.hero, .kartu').forEach(function (el, i) {
        el.style.animationDelay = Math.min(i * 60, 600) + 'ms';
    });
    function hitung(el) {
        if (el.children.length) return;
        var m = el.textContent.trim().match(/^(Rp)?\s*([\d.]+)(\s*kg)?$/);
        if (!m) return;
        var akhir = parseInt(m[2].replace(/\./g, ''), 10);
        if (isNaN(akhir) || akhir === 0) return;
        var mulai = performance.now(), dur = 1100;
        function langkah(now) {
            var p = Math.min((now - mulai) / dur, 1), e = 1 - Math.pow(1 - p, 3);
            el.textContent = (m[1] || '') + Math.round(akhir * e).toLocaleString('id-ID') + (m[3] || '');
            if (p < 1) requestAnimationFrame(langkah);
        }
        el.textContent = (m[1] || '') + '0' + (m[3] || '');
        requestAnimationFrame(langkah);
    }
    document.querySelectorAll('.kartu h3, .kartu h4').forEach(hitung);
    document.querySelectorAll('.alert-success').forEach(function (a) {
        setTimeout(function () { a.style.opacity = 0; setTimeout(function () { a.remove(); }, 500); }, 5000);
    });
})();
</script>
@stack('scripts')
</body>
</html>