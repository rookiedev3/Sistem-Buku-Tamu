@extends('layouts.app') {{-- Sesuaikan dengan nama file layout owner Anda --}}

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <div id="welcomeBanner" class="card border-0 rounded-4 p-4 shadow-sm position-relative" style="background-color: #013220; color: white;">
        <button type="button" onclick="document.getElementById('welcomeBanner').style.display='none';" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" aria-label="Close"></button>
        <div class="d-flex justify-content-between align-items-center pe-4">
            <div>
                <h4 class="fw-bold mb-1 text-white">Selamat datang, {{ Auth::user()->name ?? 'Pimpinan / Owner' }} </h4>
                <p class="mb-0 text-white-50 fs-6">Berikut adalah ringkasan aktivitas buku tamu dan kunjungan kantor hari ini.</p>
            </div>
        </div>
    </div>

    <div>
        <h5 class="fw-bold mb-3" style="color: #172033; font-size: 16px;">Ringkasan Operasional & Wawasan</h5>

        <div class="dashboard-split-wrapper">

            <div class="stats-left-grid">
                <a href="{{ route('owner.dashboard') }}#kunjungan-hari-ini" class="stat-box" style="text-decoration:none; color:inherit;">
                    <div class="stat-icon-wrap blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label-custom">Total Tamu Hari Ini</span>
                        <h3 class="stat-number-custom">{{ $totalTamuHariIni }}</h3>
                    </div>
                </a>

<a href="{{ route('owner.dashboard', ['status' => 'Terjadwal']) }}#kunjungan-hari-ini" class="stat-box" style="text-decoration:none; color:inherit;">
    <div class="stat-icon-wrap yellow">
        <i class="bi bi-hourglass-split"></i>
    </div>
    <div class="stat-content">
        <span class="stat-label-custom">Terjadwal</span>
        <h3 class="stat-number-custom">{{ $terjadwalHariIni }}</h3>
    </div>
</a>

<a href="{{ route('owner.dashboard', ['status' => 'Selesai']) }}#kunjungan-hari-ini" class="stat-box" style="text-decoration:none; color:inherit;">
    <div class="stat-icon-wrap green">
        <i class="bi bi-check-circle-fill"></i>
    </div>
    <div class="stat-content">
        <span class="stat-label-custom">Pertemuan Selesai</span>
        <h3 class="stat-number-custom">{{ $pertemuanSelesai }}</h3>
    </div>
</a>
<a href="{{ route('owner.dashboard', ['lead_only' => 1]) }}#kunjungan-hari-ini" class="stat-box" style="text-decoration:none; color:inherit;">
    <div class="stat-icon-wrap purple">
        <i class="bi bi-briefcase-fill"></i>
    </div>
    <div class="stat-content">
        <span class="stat-label-custom">Menjadi Lead</span>
        <h3 class="stat-number-custom">+{{ $menjadiLeadHariIni }}</h3>
    </div>
</a>
            </div>

            <div class="stats-right-stack">
                <a href="{{ route('products.laporan') }}" class="stat-box wide-box" style="text-decoration:none; color:inherit;">
                    <div class="stat-icon-wrap teal">
                        <i class="bi bi-fire"></i>
                    </div>
                    <div class="stat-content w-100">
                        <span class="stat-label-custom">Produk Paling Sering Diminati</span>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <h5 class="fw-bold text-dark m-0" style="font-size: 14px;">{{ $topProduct->name ?? '-' }}</h5>
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">{{ $topProduct->total ?? 0 }} Permintaan</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('guest-categories.laporan') }}" class="stat-box wide-box" style="text-decoration:none; color:inherit;">
                    <div class="stat-icon-wrap orange">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                    <div class="stat-content w-100">
                        <span class="stat-label-custom">Dominasi Kategori Tamu</span>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <h5 class="fw-bold text-dark m-0" style="font-size: 14px;">{{ $topCategory->name ?? '-' }}</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">{{ $topCategoryPercentage }}% total</span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <div class="row g-4 align-items-stretch">

        <div class="col-lg-6">
            <div class="card border-0 rounded-4 p-4 shadow-sm h-100" style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 10px 30px rgba(31,53,97,0.05); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; margin: 0 0 4px; color: #172033;">Analisis Pelayanan</h3>
                    <p style="color: #778195; font-size: 12px; margin: 0 0 20px;">Kondisi performa pelayanan tamu hari ini.</p>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: #edf4ff; color: #1463ff; display: grid; place-items: center; font-weight: 900; font-size: 15px; flex: none;">{{ $avgWaitMinutes }}</div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: 800; color: #172033; margin: 0 0 2px;">Rata-rata waktu tunggu</h4>
                                <p style="font-size: 12px; color: #778195; margin: 0; line-height: 1.4;">{{ $avgWaitMinutes }} menit sebelum bertemu PIC.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: #e8f8f1; color: #21a86b; display: grid; place-items: center; font-weight: 900; font-size: 15px; flex: none;">{{ $serviceRate }}</div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: 800; color: #172033; margin: 0 0 2px;">Tingkat pelayanan</h4>
                                <p style="font-size: 12px; color: #778195; margin: 0; line-height: 1.4;">{{ $serviceRate }}% tamu telah diselesaikan.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: #f5f3ff; color: #7c3aed; display: grid; place-items: center; font-weight: 900; font-size: 15px; flex: none;">{{ $conversionRate }}</div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: 800; color: #172033; margin: 0 0 2px;">Conversion rate</h4>
                                <p style="font-size: 12px; color: #778195; margin: 0; line-height: 1.4;">{{ $conversionRate }}% kunjungan menjadi lead.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 rounded-4 p-4 shadow-sm h-100" style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 10px 30px rgba(31,53,97,0.05); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold m-0" style="color: #172033; font-size: 16px;">Aktivitas Terbaru</h3>
    <a href="{{ route('owner.activity-log') }}" style="font-size: 12px; color: #013220; font-weight: 700; text-decoration: none;">Lihat Semua</a>
</div>

<div style="display: flex; flex-direction: column; gap: 12px;">
    @forelse($recentActivities as $activity)
        <div class="d-flex align-items-center gap-3 p-2.5 rounded-3" style="background: #f8fafc;">
            <div style="width: 38px; height: 38px; background: #e8f8f1; color: #21a86b; border-radius: 10px; display: grid; place-items: center; font-weight: bold; flex-shrink: 0; font-size: 12px;">
                {{ strtoupper(substr($activity->guest_name ?? '-', 0, 2)) }}
            </div>
            <div class="flex-grow-1" style="overflow: hidden;">
                <h6 class="m-0 text-dark fw-bold" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $activity->guest_name ?? 'Tanpa nama' }}@if($activity->company_name) ({{ $activity->company_name }})@endif
                </h6>
                <span class="text-muted" style="font-size: 11px;">Status diubah: {{ $activity->new_status }}</span>
            </div>
            <span class="text-muted" style="font-size: 10px; white-space: nowrap;">{{ \Carbon\Carbon::parse($activity->changed_at)->format('H:i') }}</span>
        </div>
    @empty
        <p class="text-muted mb-0" style="font-size: 12px;">Belum ada aktivitas terbaru.</p>
    @endforelse
</div>
                </div>
            </div>
        </div>

    </div>

    <div id="kunjungan-hari-ini" class="card mb-4 border-0 rounded-4" style="background:#fff; border:1px solid #e8edf5 !important; box-shadow: 0 2px 4px rgba(0,0,0,0.02); padding:24px;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
            <div>
                <h2 style="font-size:18px; font-weight:800; color:#172033; margin-bottom:4px;">Kunjungan Hari Ini</h2>
                <p style="font-size:13px; color:#778195; margin:0;">Daftar tamu yang dijadwalkan atau check-in pada hari ini.</p>
            </div>
        </div>

        <form id="filterKunjunganForm" method="GET" action="{{ route('owner.dashboard') }}" style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
    <input type="hidden" name="lead_only" id="leadOnlyInput" value="{{ $leadOnly ? '1' : '' }}">

    <div style="flex:1; min-width:220px;">
        <label style="font-size:11px; font-weight:700; color:#5c6678; display:block; margin-bottom:6px; text-transform:uppercase;">Cari Nama / Instansi</label>
        <div style="position:relative; display:flex; align-items:center;">
            <input type="text" name="keyword" id="keywordInput" value="{{ $keyword }}"
                   autocomplete="off" placeholder="Cari nama/instansi..."
                   style="width:100%; height:41px; padding:0 30px 0 14px; border:1px solid #e8edf5; border-radius:10px; font-size:13px; color:#172033; outline:none; box-sizing:border-box; background:#fbfcfe;">
            <button type="button" id="clearKeywordBtn"
                    style="display:{{ $keyword ? 'flex' : 'none' }}; position:absolute; right:10px; align-items:center; justify-content:center; width:16px; height:16px; background:#e2e8f0; border:none; border-radius:50%; color:#64748b; cursor:pointer; font-size:11px; line-height:1; padding:0;">
                &times;
            </button>
        </div>
    </div>

    <div style="flex:1; min-width:160px;">
        <label style="font-size:11px; font-weight:700; color:#5c6678; display:block; margin-bottom:6px; text-transform:uppercase;">Status</label>
        <select name="status" id="statusSelect" style="width:100%; height:41px; padding:0 14px; border:1px solid #e8edf5; border-radius:10px; font-size:13px; font-weight:700; color:#172033; background:#fff; outline:none; cursor:pointer; box-sizing:border-box;">
            <option value="">Semua Status</option>
            @foreach($statusOptions as $status)
                <option value="{{ $status }}" @selected(strtolower(trim($statusFilter)) === strtolower(trim($status)))>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    <div style="flex:1; min-width:160px;">
        <label style="font-size:11px; font-weight:700; color:#5c6678; display:block; margin-bottom:6px; text-transform:uppercase;">PIC</label>
        <select name="pic_id" id="picSelect" style="width:100%; height:41px; padding:0 14px; border:1px solid #e8edf5; border-radius:10px; font-size:13px; font-weight:700; color:#172033; background:#fff; outline:none; cursor:pointer; box-sizing:border-box;">
            <option value="">Semua PIC</option>
            @foreach($picOptions as $pic)
                <option value="{{ $pic->id }}" @selected((string) $picFilter === (string) $pic->id)>{{ $pic->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="display:flex; gap:8px;">
        <button type="submit" style="height:41px; background:#013220; color:#fff; border:none; padding:0 20px; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; box-sizing:border-box;">
            Filter
        </button>
        @if($statusFilter || $picFilter || $keyword || $leadOnly)
            <a href="{{ route('owner.dashboard') }}#kunjungan-hari-ini" id="resetFilterKunjungan" style="height:41px; background:#f1f5f9; color:#475569; text-decoration:none; padding:0 16px; border-radius:10px; font-size:13px; font-weight:700; display:inline-flex; align-items:center; box-sizing:border-box;">
                Reset
            </a>
        @endif
    </div>
</form>

        <div id="kunjunganInfoBar">
            @if($statusFilter || $picFilter || $keyword || $leadOnly)
<div style="background-color:#d4edda; color:#155724; padding:10px; border-radius:8px; font-size:12px; margin-top:16px; border:1px solid #c3e6cb;">
    Menampilkan hasil filter
    @if($keyword) untuk "<strong>{{ $keyword }}</strong>" @endif
    @if($statusFilter) status <strong>{{ $statusFilter }}</strong> @endif
    @if($picFilter) PIC tertentu @endif
    @if($leadOnly) khusus kunjungan yang <strong>menjadi lead</strong> (hot/warm) @endif
    — {{ $visits->count() }} data ditemukan.
</div>
            @endif
        </div>

        <div id="kunjunganTableWrapper" style="transition: opacity .15s ease; margin-top:20px;">
            @include('partials.kunjungan-hari-ini-table', compact('visits'))
        </div>
    </div>

</div>

<style>
/* CSS Styling khusus untuk Split Layout Dashboard */
.dashboard-split-wrapper {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 20px;
}

.stats-left-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.stats-right-stack {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.stat-box {
    background: #ffffff;
    border: 1px solid #e8edf5;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(31, 53, 97, 0.05);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.stat-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(31,53,97,0.10);
}

.wide-box {
    flex-direction: row;
    align-items: center;
    gap: 16px;
    padding: 18px 20px;
}

.stat-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 18px;
    flex-shrink: 0;
}

.stat-icon-wrap.blue { background: #edf4ff; color: #1463ff; }
.stat-icon-wrap.yellow { background: #fefce8; color: #ca8a04; }
.stat-icon-wrap.green { background: #e8f8f1; color: #21a86b; }
.stat-icon-wrap.purple { background: #f5f3ff; color: #7c3aed; }
.stat-icon-wrap.teal { background: #e0f2fe; color: #0284c7; }
.stat-icon-wrap.orange { background: #fff7ed; color: #c2410c; }

.stat-label-custom {
    font-size: 11px;
    font-weight: 700;
    color: #778195;
    display: block;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number-custom {
    font-size: 24px;
    font-weight: 900;
    color: #172033;
    margin: 0;
}

@media(max-width: 992px) {
    .dashboard-split-wrapper {
        grid-template-columns: 1fr;
    }
}
</style>
<script>
(function () {
    const form = document.getElementById('filterKunjunganForm');
    const wrapper = document.getElementById('kunjunganTableWrapper');
    if (!form || !wrapper) return;

    let activeController = null;
    let searchDebounceTimer = null;

    async function loadKunjungan(url) {
        if (activeController) activeController.abort();
        const controller = new AbortController();
        activeController = controller;

        wrapper.style.opacity = '0.5';
        try {
            const fetchUrl = new URL(url, window.location.href);
            fetchUrl.searchParams.set('partial', '1');
            fetchUrl.searchParams.set('_ts', Date.now().toString());

            const res = await fetch(fetchUrl.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store',
                signal: controller.signal
            });
            const html = await res.text();

            if (controller.signal.aborted) return;
            wrapper.innerHTML = html;

            const infoBar = document.getElementById('kunjunganInfoBar');
            const keyword = fetchUrl.searchParams.get('keyword') || '';
            const status  = fetchUrl.searchParams.get('status') || '';
            const picId   = fetchUrl.searchParams.get('pic_id') || '';
            const leadOnly = fetchUrl.searchParams.get('lead_only') || '';
            const hasFilter = keyword || status || picId || leadOnly;

            if (!hasFilter && infoBar) {
                infoBar.innerHTML = '';
            }

            const displayUrl = new URL(url, window.location.href);
            displayUrl.searchParams.delete('partial');
            history.replaceState(null, '', displayUrl.toString() + '#kunjungan-hari-ini');
        } catch (e) {
            if (e.name === 'AbortError') return;
            console.error('Gagal memuat data kunjungan:', e);
        } finally {
            if (activeController === controller) {
                wrapper.style.opacity = '1';
                activeController = null;
            }
        }
    }

    function submitForm() {
        clearTimeout(searchDebounceTimer);
        loadKunjungan(form.action + '?' + new URLSearchParams(new FormData(form)).toString());
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        submitForm();
    });

    form.querySelectorAll('select').forEach(function (select) {
        select.addEventListener('change', submitForm);
    });

    wrapper.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (!link) return;
        e.preventDefault();
        loadKunjungan(link.href);
    });

    const resetLink = document.getElementById('resetFilterKunjungan');
    if (resetLink) {
        resetLink.addEventListener('click', function (e) {
            e.preventDefault();
            clearTimeout(searchDebounceTimer);
            form.reset();
            const leadOnlyInput = document.getElementById('leadOnlyInput');
            if (leadOnlyInput) leadOnlyInput.value = '';
            loadKunjungan(form.action);
        });
    }

    const keywordInput = document.getElementById('keywordInput');
    const clearBtn = document.getElementById('clearKeywordBtn');
    if (keywordInput && clearBtn) {
        const toggleClear = () => { clearBtn.style.display = keywordInput.value ? 'flex' : 'none'; };

        keywordInput.addEventListener('input', function () {
            toggleClear();
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(submitForm, 500);
        });

        clearBtn.addEventListener('click', function () {
            clearTimeout(searchDebounceTimer);
            keywordInput.value = '';
            toggleClear();
            submitForm();
        });
    }
})();
</script>
@endsection