window.HargaLive = {
    mulai: function (url, cb, detik) {
        function ambil() {
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                .then(function (r) { if (!r.ok) throw new Error('http'); return r.json(); })
                .then(cb)
                .catch(function () { cb(null); });
        }
        ambil();
        setInterval(ambil, detik * 1000);
        document.addEventListener('visibilitychange', function () { if (!document.hidden) ambil(); });
        return ambil;
    }
};