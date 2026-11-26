@extends('layouts.sawit_top')
@section('title', 'Edukasi Sawit')

@section('content')
<div class="kartu p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h4 class="mb-1"><i class="bi bi-mortarboard-fill"></i> Modul Belajar Sawit</h4>
            <div class="text-muted">Belajar bertahap, kerjakan kuis, dan kumpulkan level</div>
        </div>
        <div style="min-width:220px">
            <div class="fw-semibold" id="level">Petani Pemula</div>
            <div class="progress" style="height:10px;background:rgba(255,255,255,.35)"><div class="progress-bar bg-warning" id="barTotal" style="width:0%"></div></div>
            <div class="small" id="txtTotal">0% selesai</div>
        </div>
    </div>
</div>
<div id="daftar" class="row g-3"></div>
<div id="belajar" style="display:none"></div>
@endsection

@push('scripts')
<script>
(function () {
    var KEY = 'sm_edu_{{ auth()->id() }}';
    var MODUL = [
        { id: 'panen', judul: 'Panen yang Tepat', ikon: 'bi-basket3-fill', warna: '#f59e0b', ringkas: 'Kenali kematangan buah dan cara memanen.',
          materi: [
            { j: 'Ciri buah matang', t: 'Tandan siap panen ditandai warna buah merah-oranye dan adanya brondolan, yaitu buah yang lepas dan jatuh di piringan pohon. Memanen buah mentah menurunkan kandungan minyak.' },
            { j: 'Teknik memanen', t: 'Gunakan dodos atau egrek sesuai tinggi pohon. Potong tangkai tandan serendah mungkin, lalu pangkas pelepah penyangga agar tidak menghalangi panen berikutnya.' },
            { j: 'Setelah panen', t: 'Kumpulkan semua brondolan karena nilainya tinggi. Kirim TBS ke pabrik secepat mungkin setelah dipanen agar mutu minyak tetap baik.' }],
          kuis: [
            { q: 'Tanda utama TBS siap dipanen adalah...', o: ['Buah masih hijau tua', 'Warna merah-oranye dan ada brondolan di piringan', 'Pelepah menguning', 'Tandan baru muncul'], a: 1, e: 'Brondolan menandakan buah sudah matang dan siap dipanen.' },
            { q: 'Setelah dipanen, TBS sebaiknya...', o: ['Disimpan beberapa minggu', 'Dijemur di kebun', 'Segera dikirim ke pabrik', 'Dicampur dengan buah mentah'], a: 2, e: 'Pengiriman cepat menjaga mutu dan kandungan minyak TBS.' }] },
        { id: 'pupuk', judul: 'Pemupukan', ikon: 'bi-droplet-half', warna: '#16a34a', ringkas: 'Waktu, cara, dan dosis memupuk.',
          materi: [
            { j: 'Mengapa memupuk', t: 'Sawit membutuhkan hara seperti nitrogen, fosfor, kalium, dan magnesium untuk tumbuh dan berbuah. Tanpa pemupukan yang cukup, hasil panen akan menurun.' },
            { j: 'Waktu dan cara', t: 'Pupuk saat tanah cukup lembap. Hindari saat hujan sangat deras karena pupuk tercuci, dan saat kemarau panjang karena pupuk sulit terserap. Tebar merata di piringan.' },
            { j: 'Dosis yang tepat', t: 'Ikuti rekomendasi sesuai umur tanaman dan, bila ada, hasil analisis daun atau tanah. Dosis berlebihan menambah biaya tanpa menambah hasil.' }],
          kuis: [
            { q: 'Pemupukan sebaiknya dihindari saat...', o: ['Tanah cukup lembap', 'Hujan sangat deras atau kemarau panjang', 'Pagi hari', 'Tanaman sehat'], a: 1, e: 'Hujan deras mencuci pupuk, kemarau panjang membuatnya tidak terserap.' },
            { q: 'Pupuk sebaiknya ditebar di...', o: ['Batang pohon', 'Piringan di sekitar pohon', 'Jalan panen', 'Parit'], a: 1, e: 'Piringan adalah area di sekitar pohon yang dijangkau akar penyerap hara.' }] },
        { id: 'rawat', judul: 'Perawatan Kebun', ikon: 'bi-tree-fill', warna: '#0d9488', ringkas: 'Gulma, pelepah, hama, dan penyakit.',
          materi: [
            { j: 'Piringan dan gulma', t: 'Gulma bersaing dengan sawit dalam menyerap hara dan air. Bersihkan piringan secara berkala dan rawat jalan panen agar pengangkutan lancar.' },
            { j: 'Pelepah dan sanitasi', t: 'Pangkas pelepah yang sudah tua atau kering secara teratur agar kebun bersih, panen lebih mudah, dan tempat berkembang hama berkurang.' },
            { j: 'Hama dan penyakit', t: 'Periksa kebun secara rutin. Serangan ulat pemakan daun atau penyakit seperti busuk pangkal batang lebih mudah ditangani bila ketahuan sejak awal.' }],
          kuis: [
            { q: 'Mengapa piringan harus bersih dari gulma?', o: ['Agar terlihat rapi saja', 'Gulma bersaing dengan sawit dalam hara dan air', 'Agar buah cepat matang', 'Agar tidak perlu memupuk'], a: 1, e: 'Piringan bersih membuat pupuk dan air dimanfaatkan sawit, bukan gulma.' },
            { q: 'Saat ada tanda hama atau penyakit, tindakan terbaik adalah...', o: ['Menunggu sampai parah', 'Mengabaikan', 'Memeriksa dan menangani sejak awal', 'Menebang semua pohon'], a: 2, e: 'Penanganan dini membuat biaya lebih murah dan kerugian lebih kecil.' }] },
        { id: 'kelola', judul: 'Mengelola Keuangan', ikon: 'bi-piggy-bank-fill', warna: '#8b5cf6', ringkas: 'Catat panen, biaya, dan hitung keuntungan.',
          materi: [
            { j: 'Catat setiap panen', t: 'Catat tanggal, kebun, dan berat panen. Dari catatan ini pendapatan dapat dihitung otomatis memakai harga TBS yang berlaku.' },
            { j: 'Catat semua biaya', t: 'Pisahkan biaya pupuk, transportasi, tenaga kerja, dan perawatan. Dengan begitu terlihat pos pengeluaran terbesar yang bisa dihemat.' },
            { j: 'Membaca keuntungan', t: 'Keuntungan bersih adalah pendapatan dikurangi seluruh biaya. Pantau juga perubahan harga TBS agar tahu kapan hasil kebun paling menguntungkan.' }],
          kuis: [
            { q: 'Keuntungan bersih dihitung dari...', o: ['Pendapatan dikurangi biaya', 'Pendapatan ditambah biaya', 'Hanya total biaya', 'Hanya berat panen'], a: 0, e: 'Keuntungan bersih = pendapatan - biaya.' },
            { q: 'Mengapa mencatat biaya itu penting?', o: ['Supaya terlihat sibuk', 'Agar tahu pengeluaran terbesar dan menekan pemborosan', 'Agar harga TBS naik', 'Tidak ada gunanya'], a: 1, e: 'Catatan biaya membantu mengambil keputusan penghematan.' }] }
    ];

    function muat() { try { return JSON.parse(localStorage.getItem(KEY)) || { materi: {}, skor: {} }; } catch (e) { return { materi: {}, skor: {} }; } }
    var S = muat();
    function simpan() { try { localStorage.setItem(KEY, JSON.stringify(S)); } catch (e) {} }
    function lulus(m) { return S.skor[m.id] !== undefined && S.skor[m.id] / m.kuis.length >= 0.5; }
    function persen(m) { var d = (S.materi[m.id] || []).length; return Math.round((d + (lulus(m) ? 1 : 0)) / (m.materi.length + 1) * 100); }
    function total() { var s = 0; MODUL.forEach(function (m) { s += persen(m); }); return Math.round(s / MODUL.length); }
    function levelNama(p) { return p >= 100 ? 'Ahli Sawit' : (p >= 60 ? 'Petani Mahir' : (p >= 25 ? 'Petani Terampil' : 'Petani Pemula')); }
    function $(id) { return document.getElementById(id); }

    function daftar() {
        $('belajar').style.display = 'none';
        var box = $('daftar'); box.style.display = '';
        var t = total();
        $('barTotal').style.width = t + '%'; $('txtTotal').textContent = t + '% selesai'; $('level').textContent = levelNama(t);
        box.innerHTML = MODUL.map(function (m) {
            var p = persen(m);
            return '<div class="col-md-6 col-xl-3"><div class="kartu p-3 h-100" style="border-top:5px solid ' + m.warna + '">' +
                '<div class="ikon" style="background:' + m.warna + '22;color:' + m.warna + '"><i class="bi ' + m.ikon + '"></i></div>' +
                '<h6>' + m.judul + '</h6><div class="small text-muted mb-2">' + m.ringkas + '</div>' +
                '<div class="progress mb-1" style="height:8px"><div class="progress-bar" style="width:' + p + '%;background:' + m.warna + '"></div></div>' +
                '<div class="small mb-2">' + p + '% selesai' + (p === 100 ? ' &#127941;' : '') + '</div>' +
                '<button class="btn btn-sm text-white w-100" style="background:' + m.warna + '" data-m="' + m.id + '">' + (p === 0 ? 'Mulai belajar' : (p === 100 ? 'Ulangi' : 'Lanjutkan')) + '</button></div></div>';
        }).join('');
        box.querySelectorAll('button[data-m]').forEach(function (b) {
            b.addEventListener('click', function () {
                var m = MODUL.filter(function (x) { return x.id === b.getAttribute('data-m'); })[0];
                var d = (S.materi[m.id] || []).length;
                langkah(m, Math.min(d, m.materi.length));
            });
        });
    }

    function rangka(m, i, isi) {
        $('daftar').style.display = 'none';
        var b = $('belajar'); b.style.display = '';
        var titik = '';
        for (var k = 0; k <= m.materi.length; k++) {
            var selesai = k < m.materi.length ? (S.materi[m.id] || []).indexOf(k) >= 0 : lulus(m);
            titik += '<span style="display:inline-flex;width:30px;height:30px;border-radius:50%;align-items:center;justify-content:center;margin-right:6px;font-size:.8rem;color:#fff;background:' + (k === i ? m.warna : (selesai ? '#22c55e' : '#cbd5e1')) + '">' + (k < m.materi.length ? (k + 1) : '<i class="bi bi-patch-question-fill"></i>') + '</span>';
        }
        b.innerHTML = '<div class="kartu p-4" style="border-top:5px solid ' + m.warna + '"><div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">' +
            '<div><button class="btn btn-sm btn-outline-secondary me-2" id="kembali"><i class="bi bi-arrow-left"></i> Semua modul</button><strong style="color:' + m.warna + '"><i class="bi ' + m.ikon + '"></i> ' + m.judul + '</strong></div><div>' + titik + '</div></div>' + isi + '</div>';
        $('kembali').addEventListener('click', daftar);
    }

    function langkah(m, i) {
        if (i >= m.materi.length) { kuis(m, 0, 0); return; }
        var x = m.materi[i];
        rangka(m, i, '<h5>' + (i + 1) + '. ' + x.j + '</h5><p class="fs-6">' + x.t + '</p>' +
            '<div class="d-flex justify-content-between mt-4"><button class="btn btn-outline-secondary" id="sblm" ' + (i === 0 ? 'disabled' : '') + '>Sebelumnya</button>' +
            '<button class="btn text-white" style="background:' + m.warna + '" id="lanjut">' + (i === m.materi.length - 1 ? 'Selesai, mulai kuis' : 'Selesai dan lanjut') + ' <i class="bi bi-arrow-right"></i></button></div>');
        $('sblm').addEventListener('click', function () { langkah(m, i - 1); });
        $('lanjut').addEventListener('click', function () {
            var a = S.materi[m.id] || []; if (a.indexOf(i) < 0) a.push(i); S.materi[m.id] = a; simpan();
            langkah(m, i + 1);
        });
    }

    function kuis(m, n, skor) {
        if (n >= m.kuis.length) {
            S.skor[m.id] = skor; simpan();
            var ok = lulus(m);
            rangka(m, m.materi.length, '<div class="text-center py-3"><div style="font-size:3rem">' + (ok ? '&#127942;' : '&#128170;') + '</div>' +
                '<h4>' + (ok ? 'Selamat, kamu lulus!' : 'Belum lulus, coba lagi ya') + '</h4>' +
                '<p class="text-muted">Skor kamu ' + skor + ' dari ' + m.kuis.length + '. Lulus jika benar minimal separuh.</p>' +
                '<button class="btn btn-outline-secondary me-2" id="ulang">Ulangi kuis</button><button class="btn text-white" style="background:' + m.warna + '" id="selesai">Kembali ke modul</button></div>');
            if (ok) window.toast('Modul ' + m.judul + ' selesai!', m.warna);
            $('ulang').addEventListener('click', function () { kuis(m, 0, 0); });
            $('selesai').addEventListener('click', daftar);
            return;
        }
        var q = m.kuis[n];
        rangka(m, m.materi.length, '<div class="small text-muted mb-1">Pertanyaan ' + (n + 1) + ' dari ' + m.kuis.length + '</div><h5 class="mb-3">' + q.q + '</h5>' +
            '<div class="d-grid gap-2" id="opsi">' + q.o.map(function (o, k) { return '<button class="btn btn-outline-secondary text-start" data-k="' + k + '">' + String.fromCharCode(65 + k) + '. ' + o + '</button>'; }).join('') + '</div><div id="umpan" class="mt-3"></div>');
        $('opsi').querySelectorAll('button').forEach(function (bt) {
            bt.addEventListener('click', function () {
                var k = parseInt(bt.getAttribute('data-k'), 10), benar = k === q.a;
                $('opsi').querySelectorAll('button').forEach(function (x) {
                    x.disabled = true;
                    var xk = parseInt(x.getAttribute('data-k'), 10);
                    if (xk === q.a) { x.className = 'btn btn-success text-start'; }
                    else if (xk === k) { x.className = 'btn btn-danger text-start'; }
                });
                $('umpan').innerHTML = '<div class="alert ' + (benar ? 'alert-success' : 'alert-warning') + ' py-2 mb-2">' + (benar ? 'Benar! ' : 'Kurang tepat. ') + q.e + '</div>' +
                    '<button class="btn text-white" style="background:' + m.warna + '" id="berikut">' + (n === m.kuis.length - 1 ? 'Lihat hasil' : 'Pertanyaan berikutnya') + '</button>';
                $('berikut').addEventListener('click', function () { kuis(m, n + 1, skor + (benar ? 1 : 0)); });
            });
        });
    }
    daftar();
})();
</script>
@endpush