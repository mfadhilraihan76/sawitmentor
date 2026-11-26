<?php

namespace App\Http\Controllers;

use App\Models\BiayaKebun;
use App\Models\HargaTbs;
use App\Models\JadwalPerawatan;
use App\Models\Kebun;
use App\Models\Panen;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PetaniFiturController extends Controller
{
    const KATEGORI = ['Pupuk', 'Transportasi', 'Tenaga kerja', 'Perawatan', 'Lainnya'];
    const JENIS = ['Pemupukan', 'Pembersihan gulma', 'Pemeriksaan tanaman', 'Perawatan kebun'];

    public static function tips(): array
    {
        return [
            ['judul' => 'Menentukan buah sawit matang', 'ringkas' => 'Pilih tandan yang sudah memiliki brondolan (buah lepas) di tanah.', 'isi' => 'Tandan siap panen ditandai warna buah merah-oranye dan adanya brondolan yang jatuh di piringan pohon. Memanen buah mentah menurunkan rendemen minyak, sedangkan buah terlalu lama dibiarkan meningkatkan asam lemak bebas.'],
            ['judul' => 'Teknik pemupukan', 'ringkas' => 'Pupuk sesuai dosis anjuran dan saat tanah cukup lembap.', 'isi' => 'Tebar pupuk secara merata di sekitar piringan, hindari saat hujan sangat deras atau kemarau panjang. Ikuti dosis rekomendasi sesuai umur tanaman dan hasil analisis tanah bila ada.'],
            ['judul' => 'Perawatan tanaman', 'ringkas' => 'Rutin periksa pelepah, hama, dan gejala penyakit.', 'isi' => 'Pangkas pelepah yang sudah tua atau kering dan periksa tanda serangan hama seperti ulat pemakan daun. Penanganan lebih awal membuat biaya perawatan lebih rendah.'],
            ['judul' => 'Pembersihan gulma', 'ringkas' => 'Jaga piringan pohon tetap bersih agar pupuk terserap baik.', 'isi' => 'Gulma di piringan bersaing dengan sawit dalam menyerap hara dan air. Bersihkan piringan secara berkala dan rawat jalan panen agar pengangkutan lancar.'],
            ['judul' => 'Penanganan setelah panen', 'ringkas' => 'Kirim TBS ke pabrik secepat mungkin setelah dipanen.', 'isi' => 'Buah yang terlalu lama menunggu diangkut akan mengalami penurunan mutu. Usahakan TBS tiba di pabrik maksimal sehari setelah panen dan hindari buah rusak atau tertumpuk berlebihan.'],
            ['judul' => 'Mencatat kebun dengan baik', 'ringkas' => 'Catat panen dan biaya agar keuntungan kebun terukur.', 'isi' => 'Catatan panen, biaya pupuk, dan transportasi membantu menghitung keuntungan bersih per periode dan menentukan kapan perlu menambah atau mengurangi biaya perawatan.'],
        ];
    }

    private function petani()
    {
        $u = auth()->user();
        $t = (new Petani)->getTable();
        $p = null;
        if (Schema::hasColumn($t, 'user_id')) {
            $p = Petani::where('user_id', $u->id)->first();
        }
        if (!$p && Schema::hasColumn($t, 'email')) {
            $p = Petani::where('email', $u->email)->first();
        }
        if (!$p) {
            $p = Petani::where('nama', $u->name)->first();
        }
        return $p;
    }

    private function kebunList()
    {
        $p = $this->petani();
        return $p ? Kebun::where('petani_id', $p->id)->orderBy('nama_kebun')->get() : collect();
    }

    private function kebunValid($id)
    {
        return $id && $this->kebunList()->contains('id', (int) $id) ? (int) $id : null;
    }

    private function hargaSekarang()
    {
        return HargaTbs::where('periode_mulai', '<=', today())->where('periode_selesai', '>=', today())->first();
    }

    private function hargaSebelumnya($sekarang)
    {
        return HargaTbs::where('periode_mulai', '<', $sekarang ? $sekarang->periode_mulai : today())
            ->orderByDesc('periode_mulai')->first();
    }

    // ===== Harga TBS =====
    public function hargaTbs()
    {
        $sekarang = $this->hargaSekarang();
        $sebelumnya = $this->hargaSebelumnya($sekarang);
        $perubahan = ($sekarang && $sebelumnya) ? $sekarang->harga_per_kg - $sebelumnya->harga_per_kg : null;
        $grafik = HargaTbs::orderByDesc('periode_mulai')->take(8)->get()->reverse()->values();
        $harga = HargaTbs::orderByDesc('periode_mulai')->paginate(10);
        return view('petani.harga', compact('sekarang', 'sebelumnya', 'perubahan', 'grafik', 'harga'));
    }

    public function hargaData()
    {
        $s = $this->hargaSekarang();
        $b = $this->hargaSebelumnya($s);
        $fmt = fn($h) => $h ? [
            'mulai' => $h->periode_mulai->format('d/m/Y'),
            'selesai' => $h->periode_selesai->format('d/m/Y'),
            'harga' => (float) $h->harga_per_kg,
            'label' => $h->periode_mulai->format('d/m/y'),
        ] : null;
        return response()->json([
            'sekarang' => $fmt($s),
            'sebelumnya' => $fmt($b),
            'perubahan' => ($s && $b) ? (float) ($s->harga_per_kg - $b->harga_per_kg) : null,
            'grafik' => HargaTbs::orderByDesc('periode_mulai')->take(8)->get()->reverse()->values()->map($fmt)->values(),
            'riwayat' => HargaTbs::orderByDesc('periode_mulai')->take(10)->get()->map($fmt)->values(),
            'waktu' => now()->format('H:i:s'),
        ]);
    }

    // ===== Keuangan =====
    public function keuangan()
    {
        $petani = $this->petani();
        $kebun = $this->kebunList();
        $pendapatan = $kebun->isEmpty() ? 0 : (float) Panen::whereIn('kebun_id', $kebun->pluck('id'))->sum('total_pendapatan');
        $q = BiayaKebun::where('user_id', auth()->id());
        $totalBiaya = (float) (clone $q)->sum('jumlah');
        $perKategori = (clone $q)->selectRaw('kategori, SUM(jumlah) as total')->groupBy('kategori')->pluck('total', 'kategori');
        $biaya = (clone $q)->orderByDesc('tanggal')->orderByDesc('id')->paginate(10);
        $laba = $pendapatan - $totalBiaya;
        $kategori = self::KATEGORI;
        return view('petani.keuangan', compact('petani', 'kebun', 'pendapatan', 'totalBiaya', 'laba', 'perKategori', 'biaya', 'kategori'));
    }

    public function keuanganStore(Request $request)
    {
        $d = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|in:' . implode(',', self::KATEGORI),
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
            'kebun_id' => 'nullable|integer',
        ]);
        $d['kebun_id'] = $this->kebunValid($d['kebun_id'] ?? null);
        $d['user_id'] = auth()->id();
        BiayaKebun::create($d);
        return redirect()->route('petani.keuangan')->with('success', 'Biaya berhasil dicatat.');
    }

    public function keuanganDestroy($biaya)
    {
        BiayaKebun::where('user_id', auth()->id())->findOrFail($biaya)->delete();
        return redirect()->route('petani.keuangan')->with('success', 'Biaya berhasil dihapus.');
    }

    // ===== Jadwal perawatan =====
    public function jadwal()
    {
        $kebun = $this->kebunList();
        $namaKebun = $kebun->pluck('nama_kebun', 'id');
        $jadwal = JadwalPerawatan::where('user_id', auth()->id())->orderBy('selesai')->orderBy('tanggal')->get();
        $jenis = self::JENIS;
        return view('petani.jadwal', compact('kebun', 'namaKebun', 'jadwal', 'jenis'));
    }

    public function jadwalStore(Request $request)
    {
        $d = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:' . implode(',', self::JENIS),
            'tanggal' => 'required|date',
            'kebun_id' => 'nullable|integer',
        ]);
        $d['kebun_id'] = $this->kebunValid($d['kebun_id'] ?? null);
        $d['user_id'] = auth()->id();
        JadwalPerawatan::create($d);
        return redirect()->route('petani.jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function jadwalToggle($jadwal)
    {
        $j = JadwalPerawatan::where('user_id', auth()->id())->findOrFail($jadwal);
        $j->update(['selesai' => !$j->selesai]);
        return redirect()->route('petani.jadwal');
    }

    public function jadwalDestroy($jadwal)
    {
        JadwalPerawatan::where('user_id', auth()->id())->findOrFail($jadwal)->delete();
        return redirect()->route('petani.jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }

    // ===== Edukasi =====
    public function edukasi()
    {
        $artikel = self::tips();
        return view('petani.edukasi', compact('artikel'));
    }

    // ===== Notifikasi (dihitung otomatis) =====
    public function notifikasi()
    {
        $notif = [];
        if (!$this->petani()) {
            $notif[] = ['bi-person-exclamation', 'warning', 'Akun belum terhubung', 'Data petani untuk akun ini belum terhubung oleh admin, sehingga data panen belum tampil.'];
        }

        $sekarang = $this->hargaSekarang();
        if (!$sekarang) {
            $notif[] = ['bi-cash-coin', 'secondary', 'Harga TBS belum tersedia', 'Belum ada harga TBS yang berlaku hari ini.'];
        } else {
            $sebelumnya = $this->hargaSebelumnya($sekarang);
            $teks = 'Harga TBS saat ini Rp' . number_format($sekarang->harga_per_kg, 0, ',', '.') . '/kg.';
            if ($sebelumnya) {
                $selisih = $sekarang->harga_per_kg - $sebelumnya->harga_per_kg;
                $arah = $selisih > 0 ? 'naik' : ($selisih < 0 ? 'turun' : 'tidak berubah');
                $teks .= ' Harga ' . $arah . ($selisih != 0 ? ' Rp' . number_format(abs($selisih), 0, ',', '.') : '') . ' dari periode sebelumnya.';
            }
            $notif[] = ['bi-graph-up', 'success', 'Informasi harga TBS', $teks];
        }

        $jadwal = JadwalPerawatan::where('user_id', auth()->id())->where('selesai', false)->orderBy('tanggal')->get();
        foreach ($jadwal as $j) {
            if ($j->tanggal->lt(today())) {
                $notif[] = ['bi-exclamation-triangle', 'danger', 'Jadwal terlewat', $j->judul . ' (' . $j->tanggal->format('d/m/Y') . ') belum ditandai selesai.'];
            } elseif ($j->tanggal->lte(today()->addDays(3))) {
                $notif[] = ['bi-calendar-event', 'info', 'Jadwal mendekat', $j->judul . ' dijadwalkan pada ' . $j->tanggal->format('d/m/Y') . '.'];
            }
        }
        return view('petani.notifikasi', compact('notif'));
    }
}