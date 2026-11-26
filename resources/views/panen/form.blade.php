<div class="mb-3">
    <label class="form-label">Kebun</label>
    <select name="kebun_id" class="form-select" required>
        <option value="">-- Pilih kebun --</option>
        @foreach ($kebun as $k)
            <option value="{{ $k->id }}" @selected(old('kebun_id', $panen->kebun_id ?? '') == $k->id)>{{ $k->nama_kebun }} ({{ $k->petani->nama ?? '-' }})</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Tanggal Panen</label>
    <input type="date" name="tanggal_panen" class="form-control"
           value="{{ old('tanggal_panen', isset($panen) ? $panen->tanggal_panen->format('Y-m-d') : '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Berat (kg)</label>
    <input type="number" step="0.01" min="0.01" name="berat_kg" class="form-control" value="{{ old('berat_kg', $panen->berat_kg ?? '') }}" required>
    <div class="form-text">Harga dan total pendapatan dihitung otomatis dari harga TBS pada tanggal panen.</div>
</div>
<div class="d-flex justify-content-between">
    <a href="{{ route('panen.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-success">Simpan</button>
</div>