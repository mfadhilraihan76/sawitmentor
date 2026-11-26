<div class="mb-3">
    <label class="form-label">Petani</label>
    <select name="petani_id" class="form-select" required>
        <option value="">-- Pilih petani --</option>
        @foreach ($petani as $p)
            <option value="{{ $p->id }}" @selected(old('petani_id', $kebun->petani_id ?? '') == $p->id)>{{ $p->nama }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Nama Kebun</label>
    <input type="text" name="nama_kebun" class="form-control" value="{{ old('nama_kebun', $kebun->nama_kebun ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Lokasi</label>
    <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $kebun->lokasi ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Luas Lahan (hektar)</label>
    <input type="number" step="0.01" min="0.01" name="luas_lahan" class="form-control" value="{{ old('luas_lahan', $kebun->luas_lahan ?? '') }}" required>
</div>
<div class="d-flex justify-content-between">
    <a href="{{ route('kebun.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-success">Simpan</button>
</div>