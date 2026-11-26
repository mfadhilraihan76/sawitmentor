@extends('layouts.sawit_top')
@section('title', 'Laporan Saya')
@section('warna', '#1f8f4e')

@section('menu')
    @include('petani._menu')
@endsection

@section('content')
<div class="kartu p-4 mb-4 no-print">
    <h4 class="mb-3">Laporan Saya</h4>
    <form method="GET" action="{{ route('petani.laporan') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Dari tanggal</label>
            <input type="date" name="dari" value="{{ $dari }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai }}" class="form-control">
        </div>
        <div class="col-md-6 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-funnel"></i> Terapkan</button>
            <a href="{{ route('petani.laporan') }}" class="btn btn-outline-secondary">Reset</a>
            <button type="button" onclick="window.print()" class="btn btn-dark ms-auto"><i class="bi bi-printer"></i> Cetak</button>
        </div>
    </form>
</div>

<div class="kartu p-4">
    <div class="text-center mb-3 d-none d-print-block">
        <img src="{{ asset('images/logo.png') }}" alt="SawitMentor" style="height:70px">
    </div>
    <h5 class="mb-1">Laporan Panen {{ $petani->nama ?? $user->name }}</h5>
    <div class="text-muted mb-3">
        Periode:
        {{ $dari ? \Carbon\Carbon::parse($dari)->format('d/m/Y') : 'awal' }}
        s/d
        {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d/m/Y') : 'sekarang' }}
    </div>
    <table class="table">
        <thead><tr><th>Tanggal</th><th>Kebun</th><th>Berat (kg)</th><th>Harga/kg</th><th>Pendapatan</th></tr></thead>
        <tbody>
            @forelse ($rows as $r)
                <tr>
                    <td>{{ $r['tanggal'] ? $r['tanggal']->format('d/m/Y') : '-' }}</td>
                    <td>{{ $r['kebun'] }}</td>
                    <td>{{ number_format($r['berat'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($r['harga'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($r['pendapatan'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
        @if ($rows->count())
        <tfoot>
            <tr class="fw-bold">
                <td colspan="2">Total</td>
                <td>{{ number_format($totalBerat, 0, ',', '.') }}</td>
                <td></td>
                <td>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
@endsection