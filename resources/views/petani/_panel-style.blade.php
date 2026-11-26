<style>
.page-head { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:1rem; padding:1.5rem; margin-bottom:1.5rem; color:#fff; border-radius:16px; background:linear-gradient(120deg,var(--warna),var(--warna2),var(--warna)); background-size:200% 200%; animation:geser 12s ease infinite, naik .6s ease both; box-shadow:0 8px 22px rgba(0,0,0,.12); position:relative; overflow:hidden; }
.page-head::after { content:''; position:absolute; right:-30px; top:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.18); pointer-events:none; }
.page-head h1 { font-size:1.5rem; font-weight:700; margin:0 0 .15rem; }
.page-head p { margin:0; opacity:.9; font-size:.92rem; max-width:640px; }
.page-head .btn-sawit { background:#fff; color:var(--warna); border:0; font-weight:600; position:relative; z-index:1; }
.page-head .btn-outline-secondary { color:#fff; border-color:rgba(255,255,255,.75); position:relative; z-index:1; }
.page-head .btn-outline-secondary:hover { background:rgba(255,255,255,.2); color:#fff; }
.page-head .btn-dark { position:relative; z-index:1; }

.kartu { padding:1.25rem; border-top:4px solid var(--a, var(--warna)); }
.kartu.p-0 { padding:0; }
.form-card { max-width:640px; }
.kartu.stat .lbl { font-size:.85rem; color:#6b7280; }
.kartu.stat .val { font-size:1.5rem; font-weight:700; line-height:1.3; }
.sub { font-size:.82rem; color:#6b7280; }

.btn-sawit { background:linear-gradient(135deg,var(--warna),var(--warna2)); border:0; color:#fff; border-radius:10px; padding:.45rem 1rem; transition:transform .15s, box-shadow .15s; }
.btn-sawit:hover, .btn-sawit:focus { background:linear-gradient(135deg,var(--warna2),var(--warna)); color:#fff; transform:translateY(-1px); box-shadow:0 5px 12px rgba(0,0,0,.14); }

.filter { display:flex; flex-wrap:wrap; gap:.75rem; align-items:flex-end; background:#fff; border-radius:16px; padding:1rem 1.25rem; margin-bottom:1rem; box-shadow:0 3px 10px rgba(0,0,0,.06); }
.filter label { display:block; font-size:.78rem; color:#6b7280; margin-bottom:.2rem; }
.filter .form-control, .filter .form-select { min-width:170px; }
.filter input[type=search] { min-width:260px; }

.tbl { width:100%; border-collapse:collapse; }
.tbl th { background:color-mix(in srgb, var(--warna) 13%, white); padding:.8rem 1rem; font-weight:600; font-size:.88rem; white-space:nowrap; }
.tbl td { padding:.8rem 1rem; border-top:1px solid #eef0f4; vertical-align:middle; }
.tbl tbody tr { transition:background .15s; }
.tbl tbody tr:hover { background:color-mix(in srgb, var(--warna) 7%, white); }
.tbl tfoot td { padding:.8rem 1rem; font-weight:700; border-top:2px solid #e5e7eb; }
.tbl .num { text-align:right; }

.aksi { display:flex; gap:.4rem; justify-content:flex-end; }
.aksi form { margin:0; }
.btn-icon { width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; border:1px solid #e5e7eb; background:#fff; border-radius:10px; color:#444; transition:all .15s; cursor:pointer; text-decoration:none; }
.btn-icon:hover { background:var(--warna); border-color:var(--warna); color:#fff; transform:translateY(-1px); }
.btn-icon.hapus:hover { background:#ef4444; border-color:#ef4444; }
.btn-icon.ok:hover { background:#16a34a; border-color:#16a34a; }

.pill { display:inline-block; padding:.2rem .65rem; border-radius:999px; font-size:.78rem; font-weight:600; }
.pill-green { background:#dcfce7; color:#15803d; } .pill-red { background:#fee2e2; color:#b91c1c; }
.pill-amber { background:#fef3c7; color:#b45309; } .pill-gray { background:#f1f5f9; color:#64748b; }

.empty { text-align:center; padding:3rem 1rem; color:#6b7280; }
.empty > i { font-size:3rem; color:var(--warna); display:block; margin-bottom:.5rem; }
@media print { .page-head { background:none !important; color:#000; box-shadow:none; animation:none; } }
</style>