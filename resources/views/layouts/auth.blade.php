<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SawitMentor</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --forest: #0f3d24;
            --sawit: #1f8f4e;
            --sawit-dark: #17703d;
            --gold: #f2b632;
            --ink: #16241b;
            --muted: #5d6b62;
            --line: #d9e3db;
            --bg: #f3f7f2;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: var(--ink); background: #fff; margin: 0; }

        .auth { min-height: 100vh; display: grid; grid-template-columns: minmax(0, 5fr) minmax(0, 6fr); }

        /* ===== Panel kiri ===== */
        .auth-side {
            position: relative; overflow: hidden; color: #fff; padding: 3rem;
            display: flex; flex-direction: column; justify-content: space-between; gap: 2rem;
            background: linear-gradient(160deg, #156236 0%, var(--forest) 75%);
        }
        .auth-side .fronds { position: absolute; right: -90px; bottom: -110px; width: 540px; opacity: .13; pointer-events: none; }
        .auth-brand { position: relative; display: inline-flex; background: #fff; border-radius: 14px; padding: .55rem .9rem; align-self: flex-start; }
        .auth-brand img { height: 38px; width: auto; display: block; }
        .auth-copy { position: relative; }
        .auth-copy h1 { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 700; font-size: clamp(1.9rem, 2.8vw, 2.7rem); line-height: 1.12; margin: 0 0 1rem; max-width: 16ch; }
        .auth-copy p { color: rgba(255,255,255,.78); max-width: 38ch; margin: 0; line-height: 1.6; }
        .auth-points { position: relative; list-style: none; padding: 0; margin: 0; display: grid; gap: .9rem; }
        .auth-points li { display: flex; align-items: center; gap: .85rem; font-size: .95rem; color: rgba(255,255,255,.92); }
        .auth-points .ic { flex: none; width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; background: rgba(242,182,50,.18); color: var(--gold); font-size: 1.05rem; }

        /* ===== Panel kanan ===== */
        .auth-main { display: flex; align-items: center; justify-content: center; padding: 2.5rem 1.5rem; background: #fff; }
        .auth-card { width: 100%; max-width: 410px; }
        .auth-card h2 { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 700; font-size: 1.85rem; margin: 0 0 .4rem; }
        .auth-card .sub { color: var(--muted); margin: 0 0 1.75rem; }

        .field { margin-bottom: 1.1rem; }
        .field label { display: block; font-weight: 600; font-size: .875rem; margin-bottom: .4rem; }
        .field .wrap { position: relative; }
        .field .wrap > i.lead { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #8a998f; pointer-events: none; }
        .field input.form-control {
            height: 48px; padding-left: 2.8rem; border-radius: 12px; border: 1.5px solid var(--line);
            background: #fbfdfb; font-size: .95rem; transition: border-color .15s, box-shadow .15s;
        }
        .field input.form-control:focus { border-color: var(--sawit); box-shadow: 0 0 0 4px rgba(31,143,78,.14); background: #fff; }
        .field input.form-control.is-invalid { border-color: #d64545; background-image: none; }
        .field .toggle {
            position: absolute; right: .5rem; top: 50%; transform: translateY(-50%);
            border: 0; background: transparent; color: #8a998f; width: 36px; height: 36px; border-radius: 8px;
        }
        .field .toggle:hover { color: var(--sawit); }
        .field .err { color: #c0392b; font-size: .8rem; margin-top: .35rem; }

        .check { display: flex; align-items: center; gap: .55rem; font-size: .9rem; color: var(--muted); margin: .25rem 0 1.4rem; }
        .check input { width: 1.05rem; height: 1.05rem; accent-color: var(--sawit); margin: 0; }

        .btn-sawit {
            width: 100%; height: 50px; border: 0; border-radius: 12px; font-weight: 600; font-size: 1rem; color: #fff;
            background: var(--sawit); transition: background .15s, transform .1s;
        }
        .btn-sawit:hover { background: var(--sawit-dark); }
        .btn-sawit:active { transform: translateY(1px); }
        .btn-sawit:focus-visible, .toggle:focus-visible { outline: 3px solid rgba(242,182,50,.7); outline-offset: 2px; }

        .switch { text-align: center; margin-top: 1.5rem; color: var(--muted); font-size: .92rem; }
        .switch a { color: var(--sawit-dark); font-weight: 600; text-decoration: none; }
        .switch a:hover { text-decoration: underline; }

        .alert { border-radius: 12px; font-size: .9rem; border: 0; }

        @media (max-width: 991.98px) {
            .auth { grid-template-columns: 1fr; }
            .auth-side { padding: 1.5rem; gap: 1rem; }
            .auth-side .fronds { width: 320px; right: -60px; bottom: -80px; }
            .auth-copy h1 { font-size: 1.5rem; margin-bottom: .5rem; }
            .auth-copy p, .auth-points { display: none; }
            .auth-main { padding: 2rem 1.25rem 3rem; align-items: flex-start; }
        }
    </style>
</head>
<body>
<div class="auth">
    <aside class="auth-side">
        <svg class="fronds" viewBox="0 0 400 400" aria-hidden="true">
            <g transform="translate(380 380)" fill="#fff">
                @foreach ([180, 195, 210, 225, 240, 255, 270] as $deg)
                    <path d="M0 0 C 40 -56 170 -86 330 -58 C 175 -38 60 -18 0 0 Z" transform="rotate({{ $deg }})"/>
                @endforeach
            </g>
        </svg>

        <a class="auth-brand" href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="SawitMentor"></a>

        <div class="auth-copy">
            <h1>@yield('side_title', 'Kebun sawit Anda, tercatat rapi.')</h1>
            <p>@yield('side_text', 'Satu tempat untuk mencatat panen, memantau harga TBS, dan mencetak laporan.')</p>
        </div>

        <ul class="auth-points">
            <li><span class="ic"><i class="bi bi-basket"></i></span> Catat hasil panen tiap kebun</li>
            <li><span class="ic"><i class="bi bi-graph-up-arrow"></i></span> Pantau harga TBS terbaru</li>
            <li><span class="ic"><i class="bi bi-file-earmark-text"></i></span> Cetak laporan per periode</li>
        </ul>
    </aside>

    <main class="auth-main">
        <div class="auth-card">
            @yield('content')
        </div>
    </main>
</div>

<script>
    document.querySelectorAll('.toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });
</script>
</body>
</html>