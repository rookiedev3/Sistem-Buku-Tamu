<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index()
    {
        $products = products::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'    => 'required|unique:products,code',
            'name'    => 'required',
            'category' => 'required',
        ]);

        try {
            $product = new products();
            $product->code       = $request->code;
            $product->name       = $request->name;
            $product->category    = $request->category;
            $product->is_active  = $request->boolean('is_active'); // checkbox: default false kalau gak dicentang
            $product->save();

            return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan Product. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $product = products::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = products::findOrFail($id);

        $request->validate([
            'code'    => 'required|unique:products,code,' . $id,
            'name'    => 'required',
            'category' => 'required',
        ]);

        try {
            $product->code      = $request->code;
            $product->name      = $request->name;
            $product->category   = $request->category;
            $product->is_active = $request->boolean('is_active');
            $product->save();

            return redirect()->route('products.index')->with('success', 'Product berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui Product. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $product = products::findOrFail($id);
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus Product. Silakan coba lagi.']);
        }
    }

public function laporan(Request $request)
{
    $month = (int) $request->input('month', now()->month);
    $year  = (int) $request->input('year', now()->year);

    $baseQuery = DB::table('visit_products')
        ->join('products', 'products.id', '=', 'visit_products.product_id')
        ->join('visits', 'visits.id', '=', 'visit_products.visit_id')
        ->join('leads', 'leads.visit_id', '=', 'visits.id')
        ->where('leads.status', 'deal')
        ->whereMonth('visits.check_in_at', $month)
        ->whereYear('visits.check_in_at', $year);

    // Total keseluruhan permintaan produk pada bulan terpilih (untuk basis persentase)
    $totalPermintaan = (clone $baseQuery)->count();

    // Data statistik produk, tanpa pagination
    $productStats = (clone $baseQuery)
        ->select('products.id', 'products.name', DB::raw('count(*) as total'))
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total')
        ->get();

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    return view('products.laporan', compact('productStats', 'totalPermintaan', 'month', 'year', 'months'));
}
}