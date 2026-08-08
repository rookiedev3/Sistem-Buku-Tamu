<div id="welcomeBanner" class="card border-0 rounded-4 p-4 mb-4 shadow-sm position-relative" style="background-color: #006B3F; color: white;">
    <button type="button" onclick="document.getElementById('welcomeBanner').style.display='none';" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" aria-label="Close"></button>

    <div class="d-flex justify-content-between align-items-center pe-4">
        <div>
            <h4 class="fw-bold mb-1 text-white">Selamat datang, {{ Auth::user()->name ?? 'Pimpinan / Owner' }} 👋</h4>
            <p class="mb-0 text-white-50 fs-6">Berikut adalah ringkasan aktivitas buku tamu dan kunjungan kantor hari ini.</p>
        </div>
    </div>
</div>