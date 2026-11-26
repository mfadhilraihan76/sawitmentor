<?php

namespace App\Http\Controllers;

use App\Models\HargaTbs;
use App\Models\Kebun;
use App\Models\Panen;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetaniAksiController extends Controller
{
    // Cari data petani milik akun login; bila belum ada dan $buat = true, dibuatkan otomatis
    private function petaniAkun(bool $buat = false)
    {
        $u = auth()->user();
        $p = Petani::where('user_id', $u->id)->first();
        if (!$p) {
            $p = Petani::whereNull('user_id')->where('nama', $u->name)->first();
            if ($p) {
                $p->user_id = $u->id;
                $p->save();
            }
        }
        if (!$p && $buat) {
            $p = new Petani();
            $p->forceFill(['user_id' => $u->id, 'nama' => $u->name, 'alamat' => '-', 'no_hp' => '-'])->save();
        }
        return $p;
    }

    private function hargaPada($tanggal)
    {
        return HargaTbs::whereDate('periode_mulai', '<=', $tanggal)->whereDate('periode_selesai', '>=', $tanggal)->first();
    }

    private function hapusFile(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/foto/') && is_file(public_path($path))) {
            @unlink(public_path($path));
        }
    }

    // ===== Tambah kebun =====
    public function kebunCreate()
    {
        return view('petani.kebun_form');
    }

    public function kebunStore(Request $request)
    {
        $d = $request->validate([
            'nama_kebun' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0.01|max:999999',
        ]);
        $p = $this->petaniAkun(true);
        $k = new Kebun();
        $k->forceFill($d + ['petani_id' => $p->id])->save();
        return redirect()->route('petani.kebun')->with('success', 'Kebun berhasil ditambahkan.');
    }

    // ===== Tambah panen =====
    public function panenCreate()
    {
        $p = $this->petaniAkun();
        $kebun = $p ? Kebun::where('petani_id', $p->id)->orderBy('nama_kebun')->get() : collect();
        $hargaHariIni = $this->hargaPada(today());
        return view('petani.panen_form', compact('kebun', 'hargaHariIni'));
    }

    public function panenStore(Request $request)
    {
        $d = $request->validate([
            'kebun_id' => 'required|integer',
            'tanggal_panen' => 'required|date|before_or_equal:today',
            'berat_kg' => 'required|numeric|min:0.01|max:99999999',
        ]);
        $p = $this->petaniAkun();
        $kebun = $p ? Kebun::where('petani_id', $p->id)->find($d['kebun_id']) : null;
        if (!$kebun) {
            return back()->withInput()->with('error', 'Kebun tidak ditemukan pada akun Anda.');
        }
        $harga = $this->hargaPada($d['tanggal_panen']);
        if (!$harga) {
            return back()->withInput()->with('error', 'Belum ada harga TBS yang berlaku pada tanggal panen tersebut. Hubungi admin.');
        }
        $m = new Panen();
        $m->forceFill([
            'kebun_id' => $kebun->id,
            'harga_tbs_id' => $harga->id,
            'tanggal_panen' => $d['tanggal_panen'],
            'berat_kg' => $d['berat_kg'],
            'total_pendapatan' => $d['berat_kg'] * $harga->harga_per_kg,
        ])->save();
        return redirect()->route('petani.kebun')->with('success', 'Hasil panen berhasil dicatat.');
    }

    // ===== Profil =====
    public function profil()
    {
        $user = auth()->user();
        $petani = $this->petaniAkun();
        return view('petani.profil', compact('user', 'petani'));
    }

    public function profilUpdate(Request $request)
    {
        $user = auth()->user();
        $d = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);
        $user->name = $d['name'];
        $user->email = $d['email'];
        $user->save();

        $p = $this->petaniAkun(true);
        $p->nama = $d['name'];
        $p->no_hp = $d['no_hp'] ?: '-';
        $p->alamat = $d['alamat'] ?: '-';
        $p->save();

        return redirect()->route('petani.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function fotoUpdate(Request $request)
    {
        $request->validate(
            ['foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'],
            ['foto.image' => 'File harus berupa gambar.', 'foto.mimes' => 'Format foto harus JPG, PNG, atau WEBP.', 'foto.max' => 'Ukuran foto maksimal 2 MB.', 'foto.required' => 'Pilih foto terlebih dahulu.']
        );
        $user = auth()->user();
        $dir = public_path('uploads/foto');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $file = $request->file('foto');
        $nama = 'u' . $user->id . '_' . time() . '.' . $file->extension();
        $file->move($dir, $nama);
        $this->hapusFile($user->foto);
        $user->foto = 'uploads/foto/' . $nama;
        $user->save();
        return redirect()->route('petani.profil')->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function fotoHapus()
    {
        $user = auth()->user();
        $this->hapusFile($user->foto);
        $user->foto = null;
        $user->save();
        return redirect()->route('petani.profil')->with('success', 'Foto profil dihapus.');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|string|min:8|confirmed',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak sama.',
        ]);
        $user = auth()->user();
        if (!Hash::check($request->password_lama, $user->password)) {
            return redirect()->to(route('petani.profil') . '#password')->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }
        $user->password = Hash::make($request->password_baru);
        $user->save();
        return redirect()->to(route('petani.profil') . '#password')->with('success', 'Password berhasil diubah.');
    }
}