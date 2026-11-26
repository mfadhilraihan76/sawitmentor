@extends('layouts.sawit_top')
@section('title', 'Notifikasi')
@section('warna', '#1f8f4e')
@section('menu') @include('petani._menu') @endsection

@section('content')
<div class="kartu p-4 mb-4">
    <h4 class="mb-1">Notifikasi</h4>
    <div class="text-muted">Informasi harga TBS dan pengingat jadwal perawatan</div>
</div>
@forelse ($notif as [$ikon, $warna, $judul, $isi])
    <div class="kartu p-3 mb-2 d-flex gap-3 align-items-start">
        <div class="text-{{ $warna }} fs-4"><i class="bi {{ $ikon }}"></i></div>
        <div><div class="fw-semibold">{{ $judul }}</div><div class="small text-muted">{{ $isi }}</div></div>
    </div>
@empty
    <div class="kartu p-4 text-center text-muted">Belum ada notifikasi.</div>
@endforelse
@endsection