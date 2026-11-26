<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', $petani->nama ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" rows="3" class="form-control" required>{{ old('alamat', $petani->alamat ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">No. HP</label>
    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $petani->no_hp ?? '') }}" required>
</div>
<button class="btn btn-sawit">Simpan</button>
<a href="{{ route('petani.index') }}" class="btn btn-outline-secondary">Batal</a>