@extends('layouts.sawit_top')
@section('title', 'Keuangan')
@section('warna', '#1f8f4e')
@section('menu') @include('petani._menu') @endsection

@section('content')
<div class="kartu p-4 mb-4">
    <h4 class="mb-1">Keuangan</h4>
    <div class="text-muted">Pendapatan dari panen dan biaya operasional kebun</div>
</div>
@include('petani._flash')

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="kartu p-3 h-100"><div class="text-muted small">Total Pendapatan</div><h4 class="mb-0 text-success">Rp{{ number_format($pendapatan, 0, ',', '.') }}</h4></div></div>
    <div class="col-md-4"><div class="kartu p-3 h-100"><div class="text-muted small">Total Biaya</div><h4 class="mb-0 text-danger">Rp{{ number_format($totalBiaya, 0, ',', '.') }}</h4></div></div>
    <div class="col-md-4"><div class="kartu p-3 h-100"><div class="text-muted small">Estimasi Keuntungan Bersih</div><h4 class="mb-0 {{ $laba < 0 ? 'text-danger' : '' }}">Rp{{ number_format($laba, 0, ',', '.') }}</h4></div></div>
</div>
@unless ($petani)
    <div class="alert alert-warning">Data petani untuk akun ini belum terhubung oleh admin, jadi pendapatan dari panen belum terhitung.</div>
@endunless

<div class="row g-3 mb-4">
    <div class="col-lg-8"><div class="kartu p-4 h-100">
        <h6>Catat Biaya</h6>
        <form method="POST" action="{{ route('petani.keuangan.store') }}" class="row g-2">
            @csrf
            <div class="col-md-4"><label class="form-label small">Tanggal</label><input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label small">Kategori</label>
                <select name="kategori" class="form-select" required>
                    @foreach ($kategori as $k)<option value="{{ $k }}" @selected(old('kategori') === $k)>{{ $k }}</option>@endforeach
                </select></div>
            <div class="col-md-4"><label class="form-label small">Jumlah (Rp)</label><input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label small">Keterangan</label><input type="text" name="keterangan" value="{{ old('keterangan') }}" class="form-control" maxlength="255"></div>
            <div class="col-md-4"><label class="form-label small">Kebun (opsional)</label>
                <select name="kebun_id" class="form-select">
                    <option value="">Semua kebun</option>
                    @foreach ($kebun as $kb)<option value="{{ $kb->id }}">{{ $kb->nama_kebun }}</option>@endforeach
                </select></div>
            <div class="col-md-2 d-flex align-items-end"><button class="btn btn-success w-100">Simpan</button></div>
        </form>
    </div></div>
    <div class="col-lg-4"><div class="kartu p-4 h-100">
        <h6>Biaya per Kategori</h6>
        @forelse ($perKategori as $nama => $total)
            <div class="d-flex justify-content-between small mb-1"><span>{{ $nama }}</span><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></div>
        @empty
            <div class="text-muted small">Belum ada biaya tercatat.</div>
        @endforelse
    </div></div>
</div>

<div class="kartu p-4">
    <h6>Riwayat Biaya</h6>
    <div class="table-responsive">
        <table class="table mb-2">
            <thead><tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th class="text-end">Jumlah</th><th></th></tr></thead>
            <tbody>
                @forelse ($biaya as $b)
                    <tr>
                        <td>{{ $b->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $b->kategori }}</td>
                        <td>{{ $b->keterangan ?: '-' }}</td>
                        <td class="text-end">Rp{{ number_format($b->jumlah, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('petani.keuangan.destroy', $b->id) }}" onsubmit="return confirm('Hapus biaya ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada biaya tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $biaya->links('pagination::bootstrap-5') }}
</div>
@endsection