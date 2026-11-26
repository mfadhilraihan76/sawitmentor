<div class="mb-3">
    <label class="form-label">Periode Mulai</label>
    <input type="date" name="periode_mulai" class="form-control"
           value="{{ old('periode_mulai', isset($harga) ? $harga->periode_mulai->format('Y-m-d') : '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Periode Selesai</label>
    <input type="date" name="periode_selesai" class="form-control"
           value="{{ old('periode_selesai', isset($harga) ? $harga->periode_selesai->format('Y-m-d') : '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Harga per kg (Rp)</label>
    <input type="number" step="1" min="1" name="harga_per_kg" class="form-control" value="{{ old('harga_per_kg', isset($harga) ? (int) $harga->harga_per_kg : '') }}" required>
</div>
<div class="d-flex justify-content-between">
    <a href="{{ route('harga-tbs.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-success">Simpan</button>
</div>