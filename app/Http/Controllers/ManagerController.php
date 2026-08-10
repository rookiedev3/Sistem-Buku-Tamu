<?php

namespace App\Http\Controllers;

use App\Models\leads;
use Illuminate\Http\Request;
use App\Models\visits;
use App\Models\notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Exports\KunjunganLaporanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ManagerController extends Controller
{
    /**
     * Status yang dianggap "sudah final" (kunjungan sudah selesai / dibatalkan).
     * Dipakai bareng di kunjungan() (arsip) supaya konsisten dengan pic/riwayat.
     */
    private const FINAL_STATUSES = [
        'completed', 'Selesai', 'Meeting Selesai',
        'cancelled', 'Dibatalkan', 'Ditolak',
    ];

    /**
     * Status yang dianggap "sudah ada hasil pertemuan" (dipakai khusus untuk Laporan,
     * BUKAN untuk arsip kunjungan). Kunjungan yang dibatalkan tidak ikut masuk laporan
     * karena tidak ada hasil/konversi yang bisa dilaporkan.
     */
    private const COMPLETED_STATUSES = ['completed', 'Selesai', 'Meeting Selesai'];

    public function dashboard(Request $request)
    {
        $selectedDate = $request->query('date', Carbon::today()->format('Y-m-d'));
        $selectedDateCarbon = Carbon::parse($selectedDate);
        $vipFilter = $request->input('vip_status', 'all');

        // PENTING (perbaikan bug "kunjungan 2 hari lagi ikut muncul hari ini"):
        // Front Office kadang mengisi check_in_at otomatis meski scheduled_at
        // sebenarnya masih di masa depan. Karena query lama memakai
        // whereDate('check_in_at', ...) OR whereDate('scheduled_at', ...),
        // kunjungan yang scheduled_at-nya masih 2 hari lagi bisa ikut nyangkut
        // di tanggal hari ini gara-gara check_in_at-nya kebetulan/otomatis
        // terisi tanggal hari ini.
        //
        // Aturan baru:
        //  - Kalau kunjungan itu punya jadwal (scheduled_at terisi), tanggal
        //    acuannya WAJIB scheduled_at, titik. check_in_at diabaikan.
        //  - Kalau tidak ada jadwal sama sekali (tamu walk-in tanpa scheduled_at),
        //    baru dipakai check_in_at sebagai acuan tanggal.
        $query = visits::with(['guest.category', 'assignedUser', 'purpose'])
            ->where(function ($q) use ($selectedDateCarbon) {
                $q->where(function ($q2) use ($selectedDateCarbon) {
                    $q2->whereNotNull('scheduled_at')
                        ->whereDate('scheduled_at', $selectedDateCarbon);
                })->orWhere(function ($q3) use ($selectedDateCarbon) {
                    $q3->whereNull('scheduled_at')
                        ->whereDate('check_in_at', $selectedDateCarbon);
                });
            });

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($vipFilter === 'vip') {
                $query->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($vipFilter === 'reguler') {
                $query->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        $visits = $query->orderBy('scheduled_at')->get();

        $totalToday = $visits->count();

        $leadDealsCount = visits::whereMonth('scheduled_at', Carbon::now()->month)
            ->where('status', 'deal')
            ->count();

        $notifications = notifications::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('manager.dashboard', compact('visits', 'totalToday', 'leadDealsCount', 'selectedDate', 'notifications'));
    }

    public function kunjungan(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
        ]);

        $vipFilter = $request->input('vip_status', 'all');

        // Halaman ini adalah ARSIP kunjungan, jadi hanya menampilkan kunjungan yang
        // statusnya sudah final (selesai ATAU dibatalkan) - sama persis dengan
        // pendekatan di pic/riwayat. Kunjungan yang masih berjalan (terjadwal,
        // menunggu, dikonfirmasi, sedang bertemu) sudah difasilitasi oleh
        // Dashboard Monitoring, jadi tidak perlu dobel tampil di sini.
        $query = visits::with(['guest.category', 'assignedUser', 'purpose', 'lead.followUps'])
            ->whereIn('status', self::FINAL_STATUSES);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('guest', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%{$keyword}%")
                       ->orWhere('company_name', 'like', "%{$keyword}%");
                })->orWhereHas('assignedUser', function ($q3) use ($keyword) {
                    $q3->where('name', 'like', "%{$keyword}%");
                });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('check_in_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_at', '<=', $request->end_date);
        }

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($vipFilter === 'vip') {
                $query->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($vipFilter === 'reguler') {
                $query->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        $perPage = (int) $request->input('per_page', 10);

        $visits = $query->orderBy('check_in_at', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        return view('manager.kunjungan', compact('visits', 'vipFilter'));
    }

    public function leadsPipeline(Request $request)
    {
        $today  = Carbon::today();
        $filter = $request->input('filter', 'active');
        $vipFilter = $request->input('vip_status', 'all');
        $perPage = (int) $request->input('per_page', 10);

        $query = leads::with([
            'guest',
            'visit',
            'owner',
            'followUps' => fn($q) => $q->orderBy('created_at', 'desc'),
        ]);

        switch ($filter) {
            case 'active':
                $query->whereNotIn('status', ['deal', 'lost']);
                break;
            case 'overdue':
                $query->whereNotIn('status', ['deal', 'lost'])
                      ->whereDate('follow_up_at', '<', $today);
                break;
            case 'today':
                $query->whereNotIn('status', ['deal', 'lost'])
                      ->whereDate('follow_up_at', $today);
                break;
            case 'upcoming':
                $query->whereNotIn('status', ['deal', 'lost'])
                      ->whereDate('follow_up_at', '>', $today);
                break;
            case 'deal':
                $query->where('status', 'deal');
                break;
            case 'lost':
                $query->where('status', 'lost');
                break;
            // 'all' => tanpa filter tambahan, tampilkan semua status termasuk lost
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('guest', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%{$keyword}%")
                       ->orWhere('company_name', 'like', "%{$keyword}%");
                })->orWhereHas('owner', function ($q3) use ($keyword) {
                    $q3->where('name', 'like', "%{$keyword}%");
                });
            });
        }

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($vipFilter === 'vip') {
                $query->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($vipFilter === 'reguler') {
                $query->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        $leads = $query->orderByRaw('follow_up_at IS NULL, follow_up_at ASC')
            ->paginate($perPage)
            ->appends($request->query());

        $countAll      = leads::count();
        $countActive   = leads::whereNotIn('status', ['deal', 'lost'])->count();
        $countOverdue  = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count();
        $countToday    = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count();
        $countUpcoming = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count();
        $countDeal     = leads::where('status', 'deal')->count();
        $countLost     = leads::where('status', 'lost')->count();

        return view('manager.leads', compact(
            'leads', 'filter',
            'countAll', 'countActive', 'countOverdue', 'countToday', 'countUpcoming', 'countDeal', 'countLost', 'vipFilter'
        ));
    }

    public function laporan(Request $request)
    {
        $month    = (int) $request->input('month', now()->month);
        $year     = (int) $request->input('year', now()->year);
        $category = (string) $request->input('category', '');
        $branchId = (string) $request->input('branch_id', '');
        $picId    = (string) $request->input('pic_id', '');

        // Laporan Kunjungan hanya menampilkan data yang sudah "jadi" - yaitu
        // kunjungan yang meeting-nya sudah selesai (sudah ada hasil/catatan).
        // Kunjungan yang masih terjadwal/menunggu/dibatalkan tidak relevan
        // untuk direkap sebagai laporan hasil kunjungan.
        $baseQuery = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products', 'branch'])
            ->whereMonth('check_in_at', $month)
            ->whereYear('check_in_at', $year)
            ->whereIn('status', self::COMPLETED_STATUSES);

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($category === 'vip') {
                $baseQuery->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($category === 'reguler') {
                $baseQuery->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        if ($branchId !== '') {
            $baseQuery->where('branch_id', $branchId);
        }

        if ($picId !== '') {
            $baseQuery->where('assigned_to', $picId);
        }

        $totalKunjungan = (clone $baseQuery)->count();
        $totalDeal = (clone $baseQuery)->whereHas('lead', fn($q) => $q->where('status', 'deal'))->count();
        $totalVip = Schema::hasColumn('guests', 'is_vip')
            ? (clone $baseQuery)->whereHas('guest', fn($q) => $q->where('is_vip', true))->count()
            : 0;

        // Conversion Rate = persentase kunjungan yang berhasil dikonversi jadi deal
        $conversionRate = $totalKunjungan > 0 ? round(($totalDeal / $totalKunjungan) * 100, 1) : 0;

        // Rata-rata Durasi = rata-rata lama pertemuan (check_in_at -> check_out_at) dalam menit.
        // Hanya menghitung kunjungan yang punya kedua data waktu tersebut.
        $avgDuration = (clone $baseQuery)
            ->whereNotNull('check_in_at')
            ->whereNotNull('check_out_at')
            ->get(['check_in_at', 'check_out_at'])
            ->avg(fn($v) => Carbon::parse($v->check_in_at)->diffInMinutes(Carbon::parse($v->check_out_at)));
        $avgDuration = $avgDuration ?? 0;

        $perPage = (int) $request->input('per_page', 15);

        $visits = $baseQuery->orderBy('check_in_at', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        $branches = \App\Models\branches::orderBy('name')->get();
        $picUsers = \App\Models\users::whereIn(
            'id',
            visits::whereNotNull('assigned_to')->distinct()->pluck('assigned_to')
        )->orderBy('name')->get();

        return view('manager.laporan', compact(
            'visits', 'month', 'year', 'category', 'branchId', 'picId', 'branches', 'picUsers',
            'totalKunjungan', 'totalDeal', 'totalVip', 'conversionRate', 'avgDuration'
        ));
    }

    public function exportExcel(Request $request)
    {
        $month    = (int) $request->input('month', now()->month);
        $year     = (int) $request->input('year', now()->year);
        $category = (string) $request->input('category', '');
        $branchId = (string) $request->input('branch_id', '');
        $picId    = (string) $request->input('pic_id', '');

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $branchName = $branchId !== '' ? optional(\App\Models\branches::find($branchId))->name : null;
        $picName    = $picId !== '' ? optional(\App\Models\users::find($picId))->name : null;

        $fileName = 'laporan-kunjungan-' . $month . '-' . $year . '.xlsx';

        // Kolom & filter status export Excel sudah disamakan dengan preview
        // halaman Laporan (lihat KunjunganLaporanExport::collection() & map()).
        return Excel::download(
            new KunjunganLaporanExport(
                $month, $year, $category, $branchId, $picId,
                $months[$month] ?? (string) $month, $branchName, $picName
            ),
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $month    = (int) $request->input('month', now()->month);
        $year     = (int) $request->input('year', now()->year);
        $category = (string) $request->input('category', '');
        $branchId = (string) $request->input('branch_id', '');
        $picId    = (string) $request->input('pic_id', '');

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Sama seperti laporan(): PDF cuma menampilkan kunjungan yang sudah selesai,
        // supaya isinya konsisten dengan preview di halaman Laporan & Export.
        $baseQuery = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products', 'branch'])
            ->whereMonth('check_in_at', $month)
            ->whereYear('check_in_at', $year)
            ->whereIn('status', self::COMPLETED_STATUSES);

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($category === 'vip') {
                $baseQuery->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($category === 'reguler') {
                $baseQuery->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        if ($branchId !== '') {
            $baseQuery->where('branch_id', $branchId);
        }
        if ($picId !== '') {
            $baseQuery->where('assigned_to', $picId);
        }

        $visits = $baseQuery->orderBy('check_in_at', 'asc')->get();

        $totalKunjungan = $visits->count();
        $totalDeal = $visits->filter(fn($v) => optional($v->lead)->status === 'deal')->count();
        $totalVip = $visits->filter(fn($v) => isset($v->guest) && $v->guest->is_vip)->count();
        $conversionRate = $totalKunjungan > 0 ? round(($totalDeal / $totalKunjungan) * 100, 1) : 0;

        $topSource = $visits->filter(fn($v) => $v->source)
            ->groupBy(fn($v) => $v->source->name)
            ->map->count()
            ->sortDesc();
        $topSourceName = $topSource->keys()->first();
        $topSourceCount = $topSource->first();

        $topPic = $visits->filter(fn($v) => $v->assignedUser)
            ->groupBy(fn($v) => $v->assignedUser->name)
            ->map->count()
            ->sortDesc();
        $topPicName = $topPic->keys()->first();
        $topPicCount = $topPic->first();

        // Rata-rata Durasi = check_in_at -> check_out_at, disamakan dengan
        // logic "Durasi" yang ditampilkan di preview halaman Laporan.
        $durations = $visits->filter(fn($v) => $v->check_in_at && $v->check_out_at)
            ->map(fn($v) => \Carbon\Carbon::parse($v->check_in_at)->diffInMinutes(\Carbon\Carbon::parse($v->check_out_at)));
        $avgDuration = $durations->count() > 0 ? round($durations->avg()) : null;

        $branchName = $branchId !== '' ? optional(\App\Models\branches::find($branchId))->name : null;
        $picName = $picId !== '' ? optional(\App\Models\users::find($picId))->name : null;

        $pdf = Pdf::loadView('manager.laporan_pdf', [
            'visits'          => $visits,
            'monthLabel'      => $months[$month] ?? $month,
            'year'            => $year,
            'category'        => $category,
            'branchName'      => $branchName,
            'picName'         => $picName,
            'totalKunjungan'  => $totalKunjungan,
            'totalDeal'       => $totalDeal,
            'totalVip'        => $totalVip,
            'conversionRate'  => $conversionRate,
            'topSourceName'   => $topSourceName,
            'topSourceCount'  => $topSourceCount,
            'topPicName'      => $topPicName,
            'topPicCount'     => $topPicCount,
            'avgDuration'     => $avgDuration,
            'generatedBy'     => auth()->user()->name ?? '-',
            'generatedAt'     => now(),
        ])->setPaper('a4', 'landscape');

        $fileName = 'laporan-kunjungan-' . $month . '-' . $year . '.pdf';

        return $pdf->download($fileName);
    }
}