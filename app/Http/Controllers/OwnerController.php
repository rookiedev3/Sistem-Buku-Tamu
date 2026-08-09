<?php

namespace App\Http\Controllers;

use App\Models\guests;
use App\Models\leads;
use App\Models\products;
use App\Models\users;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        $statusFilter = trim($request->input('status', ''));
        $picFilter    = $request->input('pic_id', '');
        $keyword      = $request->input('keyword', '');

        // Query dasar: kunjungan yang relevan hari ini (dijadwalkan atau check-in hari ini)
        $baseTodayQuery = fn () => visits::where(function ($q) use ($today) {
            $q->whereDate('scheduled_at', $today)
                ->orWhereDate('check_in_at', $today);
        });

        // ==========================================
        // 1. STAT CARDS
        // ==========================================
        $totalTamuHariIni = $baseTodayQuery()->count();

        $sedangMenunggu = $baseTodayQuery()
            ->whereIn(DB::raw('LOWER(TRIM(status))'), ['menunggu', 'waiting'])
            ->count();

        $sedangBertemu = $baseTodayQuery()
            ->whereIn(DB::raw('LOWER(TRIM(status))'), ['sedang bertemu', 'confirmed', 'dikonfirmasi'])
            ->count();

        $menjadiLeadHariIni = leads::whereDate('created_at', $today)->count();

        // ==========================================
        // 2. PRODUK PALING SERING DIMINATI
        // ==========================================
        $topProduct = DB::table('visit_products')
            ->join('products', 'products.id', '=', 'visit_products.product_id')
            ->select('products.name', DB::raw('count(*) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->first();

        // ==========================================
        // 3. DOMINASI KATEGORI TAMU
        // ==========================================
        $totalGuests = guests::count();

        $topCategory = DB::table('guests')
            ->join('guest_categories', 'guest_categories.id', '=', 'guests.guest_category_id')
            ->select('guest_categories.name', DB::raw('count(*) as total'))
            ->groupBy('guest_categories.id', 'guest_categories.name')
            ->orderByDesc('total')
            ->first();

        $topCategoryPercentage = ($topCategory && $totalGuests > 0)
            ? round(($topCategory->total / $totalGuests) * 100)
            : 0;

        // ==========================================
        // 4. ANALISIS PELAYANAN
        // ==========================================
        $waitTimes = visits::whereNotNull('check_in_at')
            ->whereNotNull('meeting_start_at')
            ->get()
            ->map(fn ($v) => Carbon::parse($v->check_in_at)->diffInMinutes(Carbon::parse($v->meeting_start_at)));

        $avgWaitMinutes = $waitTimes->count() > 0 ? round($waitTimes->avg()) : 0;

        $totalVisitsAll = visits::count();
        $completedVisits = visits::whereIn(DB::raw('LOWER(TRIM(status))'), ['completed', 'selesai', 'meeting selesai'])->count();
        $serviceRate = $totalVisitsAll > 0 ? round(($completedVisits / $totalVisitsAll) * 100) : 0;

        $totalLeadsAll = leads::count();
        $conversionRate = $totalVisitsAll > 0 ? round(($totalLeadsAll / $totalVisitsAll) * 100) : 0;

        // ==========================================
        // 5. AKTIVITAS TERBARU (dari visit_status_logs)
        // ==========================================
        $recentActivities = DB::table('visit_status_logs')
            ->join('visits', 'visits.id', '=', 'visit_status_logs.visit_id')
            ->join('guests', 'guests.id', '=', 'visits.guest_id')
            ->select(
                'guests.name as guest_name',
                'guests.company_name',
                'visit_status_logs.new_status',
                'visit_status_logs.changed_at'
            )
            ->orderByDesc('visit_status_logs.changed_at')
            ->take(5)
            ->get();

        // ==========================================
        // 6. TABEL KUNJUNGAN HARI INI (dengan filter & search)
        // ==========================================
        $visitsQuery = visits::with(['guest', 'purpose', 'assignedUser'])
            ->where(function ($q) use ($today) {
                $q->whereDate('scheduled_at', $today)
                    ->orWhereDate('check_in_at', $today);
            });

        if ($statusFilter !== '') {
            // Pakai LOWER(TRIM()) biar nggak sensitif spasi/kapital yang beda-beda dari input manual
            $visitsQuery->whereRaw('LOWER(TRIM(status)) = ?', [strtolower($statusFilter)]);
        }

        if ($picFilter !== '') {
            $visitsQuery->where('assigned_to', $picFilter);
        }

        if ($keyword !== '') {
            $visitsQuery->whereHas('guest', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%");
            });
        }

        $visits = $visitsQuery->orderBy('scheduled_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        // ==========================================
        // 7. DATA UNTUK DROPDOWN FILTER
        // ==========================================
        // Ambil status unik dalam bentuk yang sudah dibersihkan (trim), biar dropdown tidak dobel
        $statusOptions = visits::whereNotNull('status')
            ->get()
            ->pluck('status')
            ->map(fn ($s) => trim($s))
            ->filter(fn ($s) => $s !== '')
            ->unique()
            ->sort()
            ->values();

        $picOptions = users::where('role', 'pic')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('partials.ringkasan-operasional', compact(
            'totalTamuHariIni',
            'sedangMenunggu',
            'sedangBertemu',
            'menjadiLeadHariIni',
            'topProduct',
            'topCategory',
            'topCategoryPercentage',
            'avgWaitMinutes',
            'serviceRate',
            'conversionRate',
            'recentActivities',
            'visits',
            'statusOptions',
            'picOptions',
            'statusFilter',
            'picFilter',
            'keyword'
        ));
    }
}