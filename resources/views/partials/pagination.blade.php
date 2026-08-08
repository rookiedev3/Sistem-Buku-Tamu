{{-- 
    Partial pagination custom dengan dropdown "Tampilkan X Item" dan "Halaman X dari Y".
    Cara pakai: @include('partials.pagination', ['paginator' => $visits])
--}}
<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9;">

    <!-- Kiri: Tampilkan X Item dari total Y -->
    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #475569; font-weight: 600;">
        <span>Menampilkan</span>
        <select id="perPageSelect" style="padding: 6px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; color: #172033; outline: none; background: #fff; cursor: pointer;">
            @foreach([10, 25, 50, 100] as $option)
                <option value="{{ $option }}" {{ $paginator->perPage() == $option ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
        <span>Item dari total {{ number_format($paginator->total(), 0, ',', '.') }}</span>
    </div>

    <!-- Kanan: Halaman X dari Y + tombol prev/next -->
    @if($paginator->lastPage() > 1)
    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #475569; font-weight: 600;">
        <span>Halaman</span>
        <select id="pageSelect" style="padding: 6px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; color: #172033; outline: none; background: #fff; cursor: pointer;">
            @for($p = 1; $p <= $paginator->lastPage(); $p++)
                <option value="{{ $p }}" {{ $paginator->currentPage() == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endfor
        </select>
        <span>dari {{ $paginator->lastPage() }}</span>

        <a href="{{ $paginator->previousPageUrl() ?? '#' }}" style="display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; border: 1px solid #e8edf5; color: {{ $paginator->onFirstPage() ? '#cbd5e1' : '#006B3F' }}; text-decoration: none; pointer-events: {{ $paginator->onFirstPage() ? 'none' : 'auto' }};">‹</a>
        <a href="{{ $paginator->nextPageUrl() ?? '#' }}" style="display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; border: 1px solid #e8edf5; color: {{ $paginator->hasMorePages() ? '#006B3F' : '#cbd5e1' }}; text-decoration: none; pointer-events: {{ $paginator->hasMorePages() ? 'auto' : 'none' }};">›</a>
    </div>
    @endif
</div>

<script>
(function() {
    function updateUrlParam(param, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(param, value);
        if (param === 'per_page') {
            url.searchParams.set('page', 1); // reset ke halaman 1 kalau jumlah item/halaman diganti
        }
        window.location.href = url.toString();
    }

    const perPageEl = document.getElementById('perPageSelect');
    if (perPageEl) {
        perPageEl.addEventListener('change', function() {
            updateUrlParam('per_page', this.value);
        });
    }

    const pageEl = document.getElementById('pageSelect');
    if (pageEl) {
        pageEl.addEventListener('change', function() {
            updateUrlParam('page', this.value);
        });
    }
})();
</script>