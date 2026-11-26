<ul class="navbar-nav me-auto gap-lg-1">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('petani.dashboard') ? 'active' : '' }}" href="{{ route('petani.dashboard') }}"><span class="ic ic-hijau"><i class="bi bi-speedometer2"></i></span>Dashboard saya</a>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ request()->routeIs('petani.kebun*', 'petani.panen*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="ic ic-teal"><i class="bi bi-flower1"></i></span>Kebun</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item {{ request()->routeIs('petani.kebun') ? 'active' : '' }}" href="{{ route('petani.kebun') }}"><span class="ic ic-biru"><i class="bi bi-journal-richtext"></i></span>Kebun dan riwayat</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item {{ request()->routeIs('petani.kebun.create') ? 'active' : '' }}" href="{{ route('petani.kebun.create') }}"><span class="ic ic-hijau"><i class="bi bi-tree-fill"></i></span>Tambah Kebun</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('petani.panen.create') ? 'active' : '' }}" href="{{ route('petani.panen.create') }}"><span class="ic ic-oranye"><i class="bi bi-basket3-fill"></i></span>Tambah Panen</a></li>
        </ul>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ request()->routeIs('petani.keuangan*', 'petani.harga*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="ic ic-oranye"><i class="bi bi-wallet2"></i></span>Keuangan</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item {{ request()->routeIs('petani.keuangan*') ? 'active' : '' }}" href="{{ route('petani.keuangan') }}"><span class="ic ic-oranye"><i class="bi bi-wallet2"></i></span>Keuangan</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('petani.harga*') ? 'active' : '' }}" href="{{ route('petani.harga') }}"><span class="ic ic-biru"><i class="bi bi-graph-up-arrow"></i></span>Harga TBS (live)</a></li>
        </ul>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ request()->routeIs('petani.jadwal*', 'petani.edukasi') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="ic ic-ungu"><i class="bi bi-calendar-check"></i></span>Perawatan</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item {{ request()->routeIs('petani.jadwal*') ? 'active' : '' }}" href="{{ route('petani.jadwal') }}"><span class="ic ic-ungu"><i class="bi bi-calendar-check"></i></span>Jadwal perawatan</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('petani.edukasi') ? 'active' : '' }}" href="{{ route('petani.edukasi') }}"><span class="ic ic-pink"><i class="bi bi-mortarboard-fill"></i></span>Edukasi sawit</a></li>
        </ul>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('petani.notifikasi') ? 'active' : '' }}" href="{{ route('petani.notifikasi') }}"><span class="ic ic-merah"><i class="bi bi-bell-fill"></i></span>Notifikasi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('petani.laporan') ? 'active' : '' }}" href="{{ route('petani.laporan') }}"><span class="ic ic-cyan"><i class="bi bi-file-earmark-bar-graph-fill"></i></span>Laporan saya</a>
    </li>
</ul>