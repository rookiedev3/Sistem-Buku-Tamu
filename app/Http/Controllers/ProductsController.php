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

public function laporan(Request $request) // Pastikan menambahkan parameter Request $request
{
    // Mengambil limit per_page dari URL (default 10)
    $perPage = (int) $request->input('per_page', 10);

    // Hitung total KESELURUHAN permintaan produk (agar persentase tidak terbatas pada per halaman)
    $totalPermintaan = DB::table('visit_products')->count();

    // Query data statistik produk dengan pagination
    $productStats = DB::table('visit_products')
        ->join('products', 'products.id', '=', 'visit_products.product_id')
        ->select('products.id', 'products.name', DB::raw('count(*) as total'))
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total')
        ->paginate($perPage)
        ->appends($request->query());

    return view('products.laporan', compact('productStats', 'totalPermintaan'));
}
}