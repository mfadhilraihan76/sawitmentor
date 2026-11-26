@extends('layouts.sawit_top')
@section('title', $item->exists ? 'Edit kebun' : 'Tambah kebun')

@section('content')
@include('petani._panel-style')
<div class="page-head">
    <div>
        <h1>{{ $item->exists ? 'Edit kebun' : 'Tambah kebun' }}</h1>
        <p>Isi data kebun sesuai kondisi di lapangan.</p>
    </div>
</div>

<div class="kartu form-card">
    <form method="POST" action="{{ $item->exists ? route('petani.kebun.update', $item) : route('petani.kebun.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label" for="nama_kebun">Nama kebun</label>
            <input type="text" id="nama_kebun" name="nama_kebun" value="{{ old('nama_kebun', $item->nama_kebun) }}"
                   class="form-control @error('nama_kebun') is-invalid @enderror" placeholder="Contoh: Kebun Blok A" required autofocus>
            @error('nama_kebun')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}"
                   class="form-control @error('lokasi') is-invalid @enderror" placeholder="Desa / kecamatan / patokan" required>
            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label" for="luas_lahan">Luas lahan</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0.01" id="luas_lahan" name="luas_lahan"
                       value="{{ old('luas_lahan', $item->exists ? (float) $item->luas_lahan : '') }}"
                       class="form-control @error('luas_lahan') is-invalid @enderror" required>
                <span class="input-group-text">hektar</span>
            </div>
            @error('luas_lahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- PETA-PICKER --}}
        <div class="mb-4">
            <label class="form-label">Titik lokasi di peta</label>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <button type="button" class="btn btn-outline-success btn-sm" id="btnCariAlamat"><i class="bi bi-search"></i> Cari dari kolom Lokasi</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnGps"><i class="bi bi-crosshair"></i> Lokasi saya</button>
                <button type="button" class="btn btn-outline-danger btn-sm" id="btnHapusTitik"><i class="bi bi-x-circle"></i> Hapus titik</button>
            </div>
            <div id="petaPilih" style="height:320px;border-radius:12px;border:1px solid #e5e7eb"></div>
            <div class="form-text" id="petaInfo">Isi kolom Lokasi lalu klik "Cari", atau klik peta dan geser pin ke lokasi kebun.</div>
            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $item->latitude) }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $item->longitude) }}">
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sawit">Simpan</button>
            <a href="{{ route('petani.kebun') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    var latEl = document.getElementById('latitude'), lngEl = document.getElementById('longitude');
    var info = document.getElementById('petaInfo');
    var ada = latEl.value !== '' && lngEl.value !== '';
    var pusat = ada ? [parseFloat(latEl.value), parseFloat(lngEl.value)] : [0.5071, 101.4478];
    var map = L.map('petaPilih').setView(pusat, ada ? 16 : 8);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
    setTimeout(function () { map.invalidateSize(); }, 300);
    var marker = null;

    function tampilInfo() {
        info.textContent = marker ? ('Titik tersimpan: ' + latEl.value + ', ' + lngEl.value) : 'Belum ada titik. Klik peta untuk menandai kebun.';
    }
    function taruh(lat, lng, zoom) {
        latEl.value = lat.toFixed(7); lngEl.value = lng.toFixed(7);
        if (!marker) {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function () { var p = marker.getLatLng(); taruh(p.lat, p.lng); });
        } else { marker.setLatLng([lat, lng]); }
        if (zoom) map.setView([lat, lng], zoom);
        tampilInfo();
    }
    if (ada) taruh(pusat[0], pusat[1]); else tampilInfo();

    map.on('click', function (e) { taruh(e.latlng.lat, e.latlng.lng); });

    document.getElementById('btnCariAlamat').addEventListener('click', function () {
        var q = document.getElementById('lokasi').value.trim();
        if (!q) { info.textContent = 'Isi kolom Lokasi dulu.'; return; }
        info.textContent = 'Mencari lokasi...';
        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&q=' + encodeURIComponent(q))
            .then(function (r) { return r.json(); })
            .then(function (h) {
                if (!h.length) { info.textContent = 'Alamat tidak ditemukan. Klik peta untuk menandai manual.'; return; }
                taruh(parseFloat(h[0].lat), parseFloat(h[0].lon), 15);
            })
            .catch(function () { info.textContent = 'Gagal mencari alamat. Periksa koneksi internet.'; });
    });
    document.getElementById('btnGps').addEventListener('click', function () {
        if (!navigator.geolocation) { info.textContent = 'Peramban tidak mendukung deteksi lokasi.'; return; }
        navigator.geolocation.getCurrentPosition(function (p) { taruh(p.coords.latitude, p.coords.longitude, 16); },
            function () { info.textContent = 'Izin lokasi ditolak.'; }, { timeout: 8000 });
    });
    document.getElementById('btnHapusTitik').addEventListener('click', function () {
        if (marker) { map.removeLayer(marker); marker = null; }
        latEl.value = ''; lngEl.value = ''; tampilInfo();
    });
})();
</script>
@endpush