@extends('layouts.sawit_top')
@section('title', 'Harga TBS')

@section('content')
<div class="kartu p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h4 class="mb-1"><i class="bi bi-graph-up-arrow"></i> Harga TBS</h4>
            <div class="text-muted">Harga tandan buah segar yang ditetapkan admin, diperbarui otomatis</div>
        </div>
        <div class="text-end small">
            <span class="badge bg-light text-success border"><span class="titik me-1"></span>LIVE</span>
            <div class="mt-1">Diperbarui <span id="hWaktu">-</span> &bull; segar lagi <span id="hHitung">30</span> dtk</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="kartu p-3 h-100" id="kSekarang">
        <div class="text-muted small">Harga hari ini</div>
        <h3 class="mb-0" style="color:#2563eb" id="hSekarang">Rp --</h3>
        <div class="small text-muted" id="hSekarangRentang">Memuat...</div>
    </div></div>
    <div class="col-md-4"><div class="kartu p-3 h-100">
        <div class="text-muted small">Harga sebelumnya</div>
        <h3 class="mb-0" id="hSebelum">Rp --</h3>
        <div class="small text-muted" id="hSebelumRentang">-</div>
    </div></div>
    <div class="col-md-4"><div class="kartu p-3 h-100">
        <div class="text-muted small">Perubahan</div>
        <h3 class="mb-0 text-muted" id="hUbah">--</h3>
        <div class="small text-muted" id="hUbahKet">-</div>
    </div></div>
</div>

<div class="kartu p-4 mb-4">
    <h6>Perkembangan Harga TBS (Rp/kg)</h6>
    <div id="grafikPesan" class="text-muted text-center my-4">Memuat grafik...</div>
    <canvas id="grafikHarga" height="90" style="display:none"></canvas>
</div>

<div class="kartu p-4">
    <h6>Riwayat Harga</h6>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Periode mulai</th><th>Periode selesai</th><th class="text-end">Harga/kg</th></tr></thead>
            <tbody id="hTabel"><tr><td colspan="3" class="text-center text-muted">Memuat...</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/harga-live.js') }}"></script>
<script>
(function () {
    var grafik = null, terakhir = null, sisa = 30;
    function rp(n) { return 'Rp' + Math.round(n).toLocaleString('id-ID'); }
    function el(id) { return document.getElementById(id); }

    function render(d) {
        if (!d) { el('hWaktu').textContent = 'gagal memuat'; return; }
        el('hWaktu').textContent = d.waktu;
        if (d.sekarang) {
            el('hSekarang').innerHTML = rp(d.sekarang.harga) + '<span class="fs-6 fw-normal">/kg</span>';
            el('hSekarangRentang').textContent = 'Berlaku ' + d.sekarang.mulai + ' - ' + d.sekarang.selesai;
            if (terakhir !== null && terakhir !== d.sekarang.harga) {
                el('kSekarang').classList.remove('flash'); void el('kSekarang').offsetWidth; el('kSekarang').classList.add('flash');
                window.toast('Harga TBS berubah: ' + rp(d.sekarang.harga) + '/kg', d.sekarang.harga > terakhir ? '#16a34a' : '#dc2626');
            }
            terakhir = d.sekarang.harga;
        } else {
            el('hSekarang').textContent = 'Rp --';
            el('hSekarangRentang').textContent = 'Belum ada harga berlaku hari ini';
        }
        if (d.sebelumnya) {
            el('hSebelum').innerHTML = rp(d.sebelumnya.harga) + '<span class="fs-6 fw-normal">/kg</span>';
            el('hSebelumRentang').textContent = d.sebelumnya.mulai + ' - ' + d.sebelumnya.selesai;
        } else { el('hSebelum').textContent = 'Rp --'; el('hSebelumRentang').textContent = 'Belum ada data sebelumnya'; }

        var u = el('hUbah');
        if (d.perubahan === null) { u.className = 'mb-0 text-muted'; u.textContent = '--'; el('hUbahKet').textContent = '-'; }
        else if (d.perubahan > 0) { u.className = 'mb-0 text-success'; u.innerHTML = '&#9650; ' + rp(d.perubahan); el('hUbahKet').textContent = 'Naik dari periode sebelumnya'; }
        else if (d.perubahan < 0) { u.className = 'mb-0 text-danger'; u.innerHTML = '&#9660; ' + rp(Math.abs(d.perubahan)); el('hUbahKet').textContent = 'Turun dari periode sebelumnya'; }
        else { u.className = 'mb-0 text-secondary'; u.textContent = 'Tetap'; el('hUbahKet').textContent = 'Sama dengan periode sebelumnya'; }

        var tb = '';
        d.riwayat.forEach(function (h) { tb += '<tr><td>' + h.mulai + '</td><td>' + h.selesai + '</td><td class="text-end">' + rp(h.harga) + '</td></tr>'; });
        el('hTabel').innerHTML = tb || '<tr><td colspan="3" class="text-center text-muted">Belum ada data harga TBS.</td></tr>';

        if (d.grafik.length < 2) { el('grafikPesan').style.display = ''; el('grafikPesan').textContent = 'Grafik tampil setelah ada minimal dua periode harga.'; el('grafikHarga').style.display = 'none'; }
        else {
            el('grafikPesan').style.display = 'none'; el('grafikHarga').style.display = '';
            var label = d.grafik.map(function (g) { return g.label; }), nilai = d.grafik.map(function (g) { return g.harga; });
            if (!grafik) {
                grafik = new Chart(el('grafikHarga'), { type: 'line',
                    data: { labels: label, datasets: [{ label: 'Harga/kg', data: nilai, borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,.15)', fill: true, tension: .3, pointRadius: 4 }] },
                    options: { animation: { duration: 900 }, plugins: { legend: { display: false } } } });
            } else { grafik.data.labels = label; grafik.data.datasets[0].data = nilai; grafik.update(); }
        }
        sisa = 30;
    }
    HargaLive.mulai('{{ route('petani.harga.data') }}', render, 30);
    setInterval(function () { sisa = sisa > 0 ? sisa - 1 : 30; el('hHitung').textContent = sisa; }, 1000);
})();
</script>
@endpush