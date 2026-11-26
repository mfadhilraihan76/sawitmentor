@extends('layouts.sawit_top')
@section('title', $item->exists ? 'Edit hasil panen' : 'Catat hasil panen')

@section('content')
@include('petani._panel-style')
<div class="page-head">
    <div>
        <h1>{{ $item->exists ? 'Edit hasil panen' : 'Catat hasil panen' }}</h1>
        <p>Harga per kg diambil dari harga TBS yang berlaku pada tanggal panen.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="kartu">
            <form method="POST" action="{{ $item->exists ? route('petani.panen.update', $item) : route('petani.panen.store') }}">
                @csrf
                @if ($item->exists) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label" for="kebun_id">Kebun</label>
                    <select name="kebun_id" id="kebun_id" class="form-select @error('kebun_id') is-invalid @enderror" required>
                        <option value="" disabled @selected(! old('kebun_id', $item->kebun_id))>Pilih kebun</option>
                        @foreach ($kebun as $k)
                            <option value="{{ $k->id }}" @selected(old('kebun_id', $item->kebun_id) == $k->id)>{{ $k->nama_kebun }}</option>
                        @endforeach
                    </select>
                    @error('kebun_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="tanggal_panen">Tanggal panen</label>
                    <input type="date" name="tanggal_panen" id="tanggal_panen"
                           value="{{ old('tanggal_panen', $item->tanggal_panen?->format('Y-m-d')) }}"
                           class="form-control @error('tanggal_panen') is-invalid @enderror" required>
                    @error('tanggal_panen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" for="berat_kg">Berat panen</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.01" name="berat_kg" id="berat_kg"
                               value="{{ old('berat_kg', $item->exists ? (float) $item->berat_kg : '') }}"
                               class="form-control @error('berat_kg') is-invalid @enderror" placeholder="0" required>
                        <span class="input-group-text">kg</span>
                    </div>
                    @error('berat_kg')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sawit">Simpan</button>
                    <a href="{{ route('petani.panen.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="kartu" style="--a:#f2b632">
            <div class="sub">Harga TBS pada tanggal panen</div>
            <div class="fs-5 fw-bold mb-3" id="infoHarga">-</div>
            <div class="sub">Perkiraan pendapatan</div>
            <div class="fs-4 fw-bold" id="estimasi">-</div>
            <hr>
            <a href="{{ route('petani.harga-tbs.index') }}" class="small">Atur harga TBS</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var harga = @json($hargaList);
    var tgl = document.getElementById('tanggal_panen');
    var berat = document.getElementById('berat_kg');
    var info = document.getElementById('infoHarga');
    var est = document.getElementById('estimasi');
    var fmt = new Intl.NumberFormat('id-ID');

    function hitung() {
        if (!tgl.value) { info.textContent = 'Pilih tanggal panen'; est.textContent = '-'; return; }
        var h = harga.find(function (x) { return x.mulai <= tgl.value && x.selesai >= tgl.value; });
        if (!h) {
            info.innerHTML = '<span class="text-danger">Belum ada harga untuk tanggal ini</span>';
            est.textContent = '-';
            return;
        }
        info.textContent = 'Rp' + fmt.format(h.harga) + ' / kg';
        var b = parseFloat(berat.value);
        est.textContent = isNaN(b) ? '-' : 'Rp' + fmt.format(Math.round(b * h.harga));
    }

    tgl.addEventListener('input', hitung);
    berat.addEventListener('input', hitung);
    hitung();
})();
</script>
@endpush