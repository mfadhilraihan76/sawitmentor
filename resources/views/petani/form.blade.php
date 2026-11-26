<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', $petani->nama ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">No HP / WhatsApp</label>
    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $petani->no_hp ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" rows="3" class="form-control" required>{{ old('alamat', $petani->alamat ?? '') }}</textarea>
</div>
<div class="d-flex justify-content-between">
    <a href="{{ route('petani.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-success">Simpan</button>
</div>