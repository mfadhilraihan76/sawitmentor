@extends('layouts.sawit')
@section('title', 'Kebun dan Riwayat')
@section('warna', '#1f8f4e')

@section('menu')
    @include('petani._menu')
@endsection

@section('content')
<div class="kartu p-4 mb-4">
    <h4 class="mb-1">Kebun dan Riwayat</h4>
    <div class="text-muted">Berat, harga, dan pendapatan dari setiap kebun milik Anda</div>
</div>

<div class="kartu p-4 mb-4">
    <h5>Kebun Saya</h5>
    <table class="table mt-2">
        <thead><tr><th>Kebun</th><th>Luas</th><th>Total Berat (kg)</th><th>Total Pendapatan</th></tr></thead>
        <tbody>
            @forelse ($ringkasan as $k)
                <tr>
                    <td>{{ $k['nama'] }}</td>
                    <td>{{ $k['luas'] ?? '-' }}</td>
                    <td>{{ number_format($k['berat'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($k['pendapatan'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">Belum ada kebun terdaftar.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="kartu p-4">
    <h5>Riwayat Panen</h5>
    <table class="table mt-2">
        <thead><tr><th>Tanggal</th><th>Kebun</th><th>Berat (kg)</th><th>Harga/kg</th><th>Pendapatan</th></tr></thead>
        <tbody>
            @forelse ($riwayat as $r)
                <tr>
                    <td>{{ $r['tanggal'] ? $r['tanggal']->format('d/m/Y') : '-' }}</td>
                    <td>{{ $r['kebun'] }}</td>
                    <td>{{ number_format($r['berat'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($r['harga'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($r['pendapatan'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat panen.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection