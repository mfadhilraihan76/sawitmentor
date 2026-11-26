@extends('layouts.sawit_top')
@section('title', 'Jadwal Perawatan')
@section('warna', '#1f8f4e')
@section('menu') @include('petani._menu') @endsection

@section('content')
<div class="kartu p-4 mb-4">
    <h4 class="mb-1">Jadwal Perawatan</h4>
    <div class="text-muted">Pengingat pemupukan, pembersihan gulma, dan pemeriksaan tanaman</div>
</div>
@include('petani._flash')

<div class="kartu p-4 mb-4">
    <h6>Tambah Jadwal</h6>
    <form method="POST" action="{{ route('petani.jadwal.store') }}" class="row g-2">
        @csrf
        <div class="col-md-3"><label class="form-label small">Jenis</label>
            <select name="jenis" class="form-select" required>
                @foreach ($jenis as $j)<option value="{{ $j }}" @selected(old('jenis') === $j)>{{ $j }}</option>@endforeach
            </select></div>
        <div class="col-md-3"><label class="form-label small">Judul</label><input type="text" name="judul" value="{{ old('judul') }}" class="form-control" maxlength="255" required></div>
        <div class="col-md-2"><label class="form-label small">Tanggal</label><input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label small">Kebun (opsional)</label>
            <select name="kebun_id" class="form-select">
                <option value="">Semua kebun</option>
                @foreach ($kebun as $kb)<option value="{{ $kb->id }}">{{ $kb->nama_kebun }}</option>@endforeach
            </select></div>
        <div class="col-md-1 d-flex align-items-end"><button class="btn btn-success w-100">Tambah</button></div>
    </form>
</div>

<div class="kartu p-4">
    <h6>Daftar Jadwal</h6>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Tanggal</th><th>Kegiatan</th><th>Jenis</th><th>Kebun</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($jadwal as $j)
                    <tr class="{{ $j->selesai ? 'text-muted' : '' }}">
                        <td>{{ $j->tanggal->format('d/m/Y') }}</td>
                        <td class="{{ $j->selesai ? 'text-decoration-line-through' : '' }}">{{ $j->judul }}</td>
                        <td>{{ $j->jenis }}</td>
                        <td>{{ $namaKebun[$j->kebun_id] ?? 'Semua kebun' }}</td>
                        <td>
                            @if ($j->selesai) <span class="badge bg-success">Selesai</span>
                            @elseif ($j->tanggal->lt(today())) <span class="badge bg-danger">Terlewat</span>
                            @else <span class="badge bg-warning text-dark">Terjadwal</span> @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <form method="POST" action="{{ route('petani.jadwal.toggle', $j->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success">{{ $j->selesai ? 'Batal selesai' : 'Tandai selesai' }}</button>
                                </form>
                                <form method="POST" action="{{ route('petani.jadwal.destroy', $j->id) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada jadwal perawatan kebun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection