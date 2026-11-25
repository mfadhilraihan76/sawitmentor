@php
// Daftar menu SawitMentor. Ubah 'url' / 'pattern' jika URL route kamu berbeda.
$menus = [
    ['header' => 'Utama'],
    ['name' => 'Dashboard', 'url' => '/dashboard', 'pattern' => 'dashboard*', 'icon' => 'ri-home-smile-line'],
    ['header' => 'Data Master'],
    ['name' => 'Petani', 'url' => '/petani', 'pattern' => 'petani*', 'icon' => 'ri-user-3-line'],
    ['name' => 'Kebun', 'url' => '/kebun', 'pattern' => 'kebun*', 'icon' => 'ri-plant-line'],
    ['header' => 'Transaksi'],
    ['name' => 'Panen', 'url' => '/panen', 'pattern' => 'panen*', 'icon' => 'ri-leaf-line'],
    ['name' => 'Harga TBS', 'url' => '/harga-tbs', 'pattern' => 'harga-tbs*', 'icon' => 'ri-price-tag-3-line'],
];
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu">

    <div class="app-brand demo">
        <a href="{{ url('/dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-semibold ms-2">{{ config('variables.templateName') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menus as $menu)
            @if (isset($menu['header']))
                <li class="menu-header mt-7">
                    <span class="menu-header-text">{{ $menu['header'] }}</span>
                </li>
            @else
                <li class="menu-item {{ request()->is($menu['pattern']) ? 'active' : '' }}">
                    <a href="{{ url($menu['url']) }}" class="menu-link">
                        <i class="menu-icon icon-base ri {{ $menu['icon'] }}"></i>
                        <div>{{ $menu['name'] }}</div>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>

</aside>
